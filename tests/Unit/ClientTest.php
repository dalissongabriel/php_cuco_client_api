<?php


namespace App\Tests\Unit;


use App\Helpers\Exception\InvalidCpfException;
use App\Helpers\Exception\InvalidEmailException;
use PHPUnit\Framework\TestCase;
use App\Entity\Client;

class ClientTest extends TestCase
{
    public function testShouldEnsureThatTheClientWillHaveAValidEmail()
    {
        $client = new Client();
        $client->setEmail("test@valid.com");
        self::assertEquals("test@valid.com", $client->getEmail());
    }

    public function testShouldEnsureValidationOfInvalidEmails()
    {
        self::expectException(InvalidEmailException::class);
        $client = new Client();
        $client->setEmail("test@");
    }

    public function testShouldEnsureThatTheClientWillHaveAValidCpf()
    {
        $client = new Client();
        $client->setCpf("123.456.789-09");
        self::assertEquals("123.456.789-09", $client->getCpf());
    }

    public function testShouldEnsureValidationOfInvalidCpfs()
    {
        self::expectException(InvalidCpfException::class);
        $client = new Client();
        $client->setCpf("123");
    }
}