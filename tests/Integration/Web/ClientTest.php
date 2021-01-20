<?php


namespace App\Tests\Integration\Web;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientTest extends WebTestCase
{
    public function testMustEnsureThatTheRequestCreateClientWithoutPhoneIsSucessful()
    {
        $client = static::createClient();

        $client->request('POST', '/clientes',[],[],[
            'CONTENT_TYPE'=>'application/json',
            'HTTP_AUTHORIZATION'=> LoginApi::login($client)
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

    public function testEnsureThatAnInvalidCpfIsHandledByTheDomainDuringTheRequest()
    {
        $client = static::createClient();

        $client->request('POST', '/clientes',[],[],[
            'CONTENT_TYPE'=>'application/json',
            'HTTP_AUTHORIZATION'=> LoginApi::login($client)
        ], json_encode([
            "name"=>"Cliente teste 3",
            "cpf"=>"1234236",
            "email"=>"teste3@valid.com"
        ]));

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertFalse($content->success);
        self::assertObjectHasAttribute("message",$content->data);
        self::assertEquals(412, $response->getStatusCode());
    }

    public function testEnsureThatAnInvalidEmailIsHandledByTheDomainDuringTheRequest()
    {
        $client = static::createClient();

        $client->request('POST', '/clientes',[],[],[
            'CONTENT_TYPE'=>'application/json',
            'HTTP_AUTHORIZATION'=> LoginApi::login($client)
        ], json_encode([
            "name"=>"Cliente teste 3",
            "cpf"=>"278.128.110-77",
            "email"=>"teste3@"
        ]));

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertFalse($content->success);
        self::assertObjectHasAttribute("message",$content->data);
        self::assertEquals(412, $response->getStatusCode());
    }

    public function testEnsureThatAnInvalidPhoneHandledByTheDomainDuringTheRequest()
    {
        $client = static::createClient();

        $client->request('POST', '/clientes',[],[],[
            'CONTENT_TYPE'=>'application/json',
            'HTTP_AUTHORIZATION'=> LoginApi::login($client)
        ], json_encode([
            "name"=>"Cliente teste 3",
            "cpf"=>"278.128.110-77",
            "email"=>"teste3@test.com",
            "phone"=>"3333322"
        ]));

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertFalse($content->success);
        self::assertObjectHasAttribute("message",$content->data);
        self::assertEquals(412, $response->getStatusCode());
    }

    public function testMustEnsureThatTheRequestCreateClientWithPhoneIsSucessful()
    {
        $client = static::createClient();

        $client->request('POST', '/clientes',[],[],[
            'CONTENT_TYPE'=>'application/json',
            'HTTP_AUTHORIZATION'=> LoginApi::login($client)
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
            [
                'CONTENT_TYPE'=>'application/json',
                'HTTP_AUTHORIZATION'=> LoginApi::login($client)
            ]
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertTrue($content->success);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testMustEnsureCatchHttpNotFoundExceptionWhenRouteDontExists()
    {
        $client = static::createClient();

        $client->request('GET', '/my-altory-route',[],[],
            [
                'CONTENT_TYPE'=>'application/json',
                'HTTP_AUTHORIZATION'=> LoginApi::login($client)
            ]
        );
        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertFalse($content->success);
        self::assertEquals(404, $response->getStatusCode());
    }

    public function testMustEnsureThatARequestWithAnInvalidBodyIsHandled()
    {
        $client = static::createClient();
        $invalidBody = "{
            'name':'bad name
        }";

        $client->request('POST', '/clientes',[],[],[
            'CONTENT_TYPE'=>'application/json',
            'HTTP_AUTHORIZATION'=> LoginApi::login($client)
        ], $invalidBody);

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertFalse($content->success);
        self::assertEquals(400, $response->getStatusCode());
    }

    public function testShouldEnsureThatAllMandatoryFieldsHaveBeenEnteredToCreatClient()
    {
        $client = static::createClient();
        $body = json_encode([
            "name"=>"Cliente teste 3",
            "email"=>"teste3@valid.com",
            "phone"=>"(40) 404044040"
        ]);

        $client->request('POST', '/clientes',[],[],[
            'CONTENT_TYPE'=>'application/json',
            'HTTP_AUTHORIZATION'=> LoginApi::login($client)
        ], $body);

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertFalse($content->success);
        self::assertObjectHasAttribute("message", $content->data);
        self::assertEquals(400, $response->getStatusCode());
    }


    public function testMustEnsureThatTheRequestGetAllClientIsSucessful()
    {
        $client = static::createClient();

        $client->request('GET', '/clientes',[],[],
            [
                'CONTENT_TYPE'=>'application/json',
                'HTTP_AUTHORIZATION'=> LoginApi::login($client)
            ]
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertTrue($content->success);
        self::assertIsArray($content->data);
        self::assertEquals(200, $response->getStatusCode());
    }

//    public function testMustEnsureThatTheRequestRemoveAClientIsSucessful()
//    {
//        $client = static::createClient();
//
//        $client->request("GET","/clientes",[],[],['CONTENT_TYPE'=>'application/json']);
//        $response = $client->getResponse();
//        $content = json_decode($response->getContent());
//        var_dump($content);
//        die();
//
//        $client->request('DELETE', '/clientes/1',[],[],
//            ['CONTENT_TYPE'=>'application/json']);
//
//        $response = $client->getResponse();
//        self::assertEquals(204, $response->getStatusCode());
//    }
}