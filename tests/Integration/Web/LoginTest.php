<?php


namespace App\Tests\Integration\Web;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testMustEnsureThatTheLoginRouteAcceptsOnlyRequestsWithMandatoryFields()
    {
        $client = static::createClient();

        $client->request('POST', '/login',[],[],[
            'CONTENT_TYPE'=>'application/json',
            'HTTP_AUTHORIZATION'=> LoginApi::login($client)
        ], json_encode([
            "login"=>"test",
            "senha"=>"password_test"
        ]));

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertFalse($content->success);
        self::assertEquals(400, $response->getStatusCode());
    }

    public function testMustEnsureThatATokenIsReturnedWhenLoggingInCorrectly()
    {
        $client = static::createClient();

        $client->request('POST', '/login',[],[],[
            'CONTENT_TYPE'=>'application/json',
            'HTTP_AUTHORIZATION'=> LoginApi::login($client)
        ], json_encode([
            "username"=>"user_cuco_api",
            "password"=>'password_cuco_api'
        ]));

        $response = $client->getResponse();
        $content = json_decode($response->getContent());
        self::assertTrue($content->success);
        self::assertObjectHasAttribute("access_token",$content->data);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testShouldEnsureThatLoginAttemptsWithInvalidCredentialsAreNotAuthorized()
    {
        $client = static::createClient();

        $client->request('POST', '/login',[],[],[
            'CONTENT_TYPE'=>'application/json',
            'HTTP_AUTHORIZATION'=> LoginApi::login($client)
        ], json_encode([
            "username"=>"my_aleatory_user",
            "password"=>'my_aleatory_password'
        ]));

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertFalse($content->success);
        self::assertObjectHasAttribute("message",$content->data);
        self::assertEquals(401, $response->getStatusCode());
    }

    public function testShouldEnsureThatAllProtectedRoutesRequireAuthorization()
    {
        $client = static::createClient();

        $client->request('GET', '/clientes',[],[],[
            'CONTENT_TYPE'=>'application/json'
        ]);

        $response = $client->getResponse();
        $content = json_decode($response->getContent());

        self::assertFalse($content->success);
        self::assertObjectHasAttribute("message",$content->data);
        self::assertEquals(401, $response->getStatusCode());
    }

}