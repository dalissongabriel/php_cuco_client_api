<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Service\ClientFactory;
use App\Service\DataExtractorRequest;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;

class ClientController extends BaseController
{

    /**
     * ClientController constructor.
     * @param ClientRepository $repository
     * @param EntityManagerInterface $entityManager
     * @param ClientFactory $factory
     * @param DataExtractorRequest $extractorRequest
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(
        ClientRepository $repository,
        EntityManagerInterface $entityManager,
        ClientFactory $factory,
        DataExtractorRequest $extractorRequest,
        CacheItemPoolInterface $cache
    ) {
        parent::__construct(
            $repository,
            $entityManager,
            $factory,
            $extractorRequest,
            $cache);
    }

    /**
     * @param Client $entity
     * @param Client $newEntity
     */
    public function updateEntity(object $entity, object $newEntity): void
    {
        $entity->setName($newEntity->getName());
        $entity->setEmail($newEntity->getEmail());
        $entity->setCpf($newEntity->getCpf());
        if(!is_null($newEntity->getPhone())) {
            $entity->setPhone($newEntity->getPhone());
        }
    }

    public function cachePrefix(): string
    {
        return "client_";
    }
}
