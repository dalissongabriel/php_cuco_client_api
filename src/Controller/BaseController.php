<?php


namespace App\Controller;

use App\Helpers\Exception\DatabaseException;
use App\Service\DataExtractorRequest;
use App\Service\EntityFactoryInterface;
use App\Service\ResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    protected ObjectRepository $repository;
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;
    /**
     * @var EntityFactoryInterface
     */
    protected EntityFactoryInterface $factory;
    /**
     * @var DataExtractorRequest
     */
    protected DataExtractorRequest $extractorRequest;
    /**
     * @var CacheItemPoolInterface
     */
    protected CacheItemPoolInterface $cache;

    /**
     * BaseController constructor.
     * @param ObjectRepository $repository
     * @param EntityManagerInterface $entityManager
     * @param EntityFactoryInterface $factory
     * @param DataExtractorRequest $extractorRequest
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntityFactoryInterface $factory,
        DataExtractorRequest $extractorRequest,
        CacheItemPoolInterface $cache
    ) {

        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extractorRequest = $extractorRequest;
        $this->cache = $cache;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function findAll(Request $request): Response
    {
        $sortInfo = $this->extractorRequest->getSortData($request);
        $filterInfo = $this->extractorRequest->getFilterData($request);

        [$page, $itemsPerPage] = $this->extractorRequest->getPaginationData($request);

        $entityList = $this->repository->findBy(
            $filterInfo,
            $sortInfo,
            $itemsPerPage,
            ($page - 1) * $itemsPerPage
        );

        $responseFactory = new ResponseFactory(
            true,
            $entityList,
            Response::HTTP_OK,
            $page,
            $itemsPerPage
        );
        return $responseFactory->getResponse();
    }

    /**
     * @param int $id
     * @return Response
     * @throws InvalidArgumentException
     */
    public function find(int $id): Response
    {
        $cacheKey = $this->cachePrefix() . $id;
        $entity =
            $this->cache->hasItem($cacheKey)
                ? $item = $this->cache->getItem($cacheKey)->get()
                : $this->repository->find($id);

        $statusCode = is_null($entity)
            ? Response::HTTP_NO_CONTENT
            : Response::HTTP_OK;

        $responseFactory = new ResponseFactory(
            true,
            $entity,
            $statusCode
        );

        return $responseFactory->getResponse();
    }

    /**
     * @param int $id
     * @return Response
     * @throws DatabaseException
     * @throws InvalidArgumentException
     */
    public function remove(int $id): Response
    {
        $entity = $this->repository->find($id);

        if (is_null($entity)) {
            $responseFactory = $this->getResponseFactoryNotFound();
            return $responseFactory->getResponse();
        }

        try {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            $this->cache->deleteItem($this->cachePrefix() . $id);

        } catch (Exception $exception) {
            throw new DatabaseException(
                "Não foi possível excluir o recurso pois ainda existem subrecursos vinculados a ele",
                Response::HTTP_PRECONDITION_REQUIRED
            );
        }

        $responseFactory = new ResponseFactory(
            true,
            $entity,
            Response::HTTP_NO_CONTENT
        );

        return $responseFactory->getResponse();
    }


    /**
     * @param Request $request
     * @return Response
     * @throws InvalidArgumentException
     */
    public function create(Request $request): Response
    {
        $content = $request->getContent();
        $entity = $this->factory->create($content);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $this->persistCache($entity);

        $responseFactory = new ResponseFactory(
            true,
            $entity,
            Response::HTTP_CREATED
        );

        return $responseFactory->getResponse();
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     * @throws InvalidArgumentException
     */
    public function update(int $id, Request $request): Response
    {
        $content = $request->getContent();
        $newEntity = $this->factory->create($content);

        $entity = $this->repository->find($id);

        if (is_null($entity)) {
            $responseFactory = $this->getResponseFactoryNotFound();
            return $responseFactory->getResponse();
        }

        $this->updateEntity($entity, $newEntity);
        $this->entityManager->flush();
        $this->persistCache($entity);

        $responseFactory = new ResponseFactory(
            true,
            $entity,
            Response::HTTP_OK
        );

        return $responseFactory->getResponse();
    }

    /**
     * @param object $entity
     * @param object $newEntity
     * @return mixed
     */
    abstract public function updateEntity(object $entity, object $newEntity);

    /**
     * @return string
     */
    abstract public function cachePrefix(): string;

    /**
     * @return ResponseFactory
     */
    public function getResponseFactoryNotFound(): ResponseFactory
    {
        $responseFactory = new ResponseFactory(
            false,
            "Entidade não foi encontrada",
            Response::HTTP_NOT_FOUND
        );
        return $responseFactory;
    }

    /**
     * @param object $entity
     * @throws InvalidArgumentException
     */
    public function persistCache(object $entity): void
    {
        $cacheItem = $this->cache->getItem($this->cachePrefix() . $entity->getId());
        $cacheItem->set($entity);
        $this->cache->save($cacheItem);
    }

}