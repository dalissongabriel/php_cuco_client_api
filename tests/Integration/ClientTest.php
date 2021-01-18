<?php


namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientTest extends WebTestCase
{
    public function testMustEnsureThatTheRequestCreateClientWithoutPhoneIsSucessful()
    {
        $client = static::createClient();

        $client->request('POST', '/clientes',[],[],[
            'CONTENT_TYPE'=>'application/json'
        ], json_encode([
            "name"=>"Cliente teste 3",
            "cpf"=>"278.128.110-77",
            "email"=>"teste3@valid.com"
        ]));

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertTrue($content->success);
        self::assertEquals(201, $response->getStatusCode());
    }

    public function testMustEnsureThatTheRequestCreateClientWithPhoneIsSucessful()
    {
        $client = static::createClient();

        $client->request('POST', '/clientes',[],[],[
            'CONTENT_TYPE'=>'application/json'
        ], json_encode([
            "name"=>"Cliente teste 3",
            "cpf"=>"278.128.110-77",
            "email"=>"teste3@valid.com",
            "phone"=>"(40) 404044040"
        ]));

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertTrue($content->success);
        self::assertObjectHasAttribute("phone",$content->data);
        self::assertEquals(201, $response->getStatusCode());
    }

    public function testMustEnsureThatTheRequestGetAClientIsSucessful()
    {
        $client = static::createClient();

        $client->request('GET', '/clientes/1',[],[],
            ['CONTENT_TYPE'=>'application/json']);

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertTrue($content->success);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testMustEnsureThatTheRequestGetAllClientIsSucessful()
    {
        $client = static::createClient();

        $client->request('GET', '/clientes',[],[],
            ['CONTENT_TYPE'=>'application/json']);

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertTrue($content->success);
        self::assertIsArray($content->data);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testMustEnsureThatTheRequestRemoveAClientIsSucessful()
    {
        $client = static::createClient();

        $client->request('DELETE', '/clientes/1',[],[],
            ['CONTENT_TYPE'=>'application/json']);

        $response = $client->getResponse();
        self::assertEquals(204, $response->getStatusCode());
    }
}