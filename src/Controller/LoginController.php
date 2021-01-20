<?php

namespace App\Controller;


use App\Entity\User;
use App\Helpers\KeyAuthenticationJWTTrait;
use App\Repository\UserRepository;
use App\Service\LoginFactory;
use App\Service\ResponseFactory;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{
    use KeyAuthenticationJWTTrait;
    /**
     * @var UserRepository
     */
    private UserRepository $repository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    /**
     * LoginController constructor.
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        UserRepository $repository,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }

    public function login(Request $request): Response
    {
        $LoginFactory = new LoginFactory(
            $this->repository,
            $this->encoder
        );

        /**
         * @var User $user
         */
        $user = $LoginFactory->create($request->getContent());

        $token = JWT::encode(
            ["username" => $user->getUsername()],
            KeyAuthenticationJWTTrait::getKey()
        );

        $responseFactory = new ResponseFactory(
            true,
            ["access_token"=>$token]
        );

        return $responseFactory->getResponse();

    }
}
