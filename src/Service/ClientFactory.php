<?php


namespace App\Service;



use App\Entity\Client;
use App\Helper\Exceptions\EntityFactoryException;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class ClientFactory implements EntityFactoryInterface
{
    /**
     * @var ClientRepository
     */
    private ClientRepository $repository;

    /**
     * ClientFactory constructor.
     * @param ClientRepository $repository
     */
    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $requestContent
     * @return Client
     */
    public function create(string $requestContent): Client
    {
        $content = json_decode($requestContent);

        if (is_null($content)) {
            throw new BadRequestException(
                "Requisição mal feita: confira o corpo da requisição",
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->checkRequiredProperties($content);

        $client = new Client();

        $client->setName($content->name)
            ->setEmail($content->email)
            ->setCpf($content->cpf);

        if (property_exists($content,"phone")) {
            $client->setPhone($content->phone);
        }

        return $client;
    }

    /**
     * @param object $content
     */
    private function checkRequiredProperties(object $content): void
    {
        if (!property_exists($content, "name")) {
            throw new EntityFactoryException(
                "Para a entidade Cliente deve ser informado o campo: name",
                Response::HTTP_BAD_REQUEST
            );
        }

        if (!property_exists($content, "cpf")) {
            throw new EntityFactoryException(
                "Para a entidade Cliente deve ser informado o campo: cpf",
                Response::HTTP_BAD_REQUEST
            );
        }

        if (!property_exists($content, "email")) {
            throw new EntityFactoryException(
                "Para a entidade Cliente deve ser informado o campo: email",
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}