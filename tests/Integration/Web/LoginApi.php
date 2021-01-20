<?php


namespace App\Tests\Integration\Web;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;

abstract class  LoginApi
{
    public static function login(KernelBrowser $client): string
    {
        $client->request('POST','/login',[],[],[
            'CONTENT_TYPE'=>'application/json'],
            json_encode([
                "username"=>"user_cuco_api",
                "password"=>"password_cuco_api"
            ]));

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        return $content->data->access_token;
    }

}