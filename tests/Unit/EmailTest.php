<?php

namespace App\Tests\Unit;

use App\Entity\Client;
use App\ValueObject\Email;
use JsonSerializable;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testShouldEnsureThatEntityIsSerializable()
    {
        $client = new Client();
        $client->setEmail("teste@email.com");

        self::assertTrue($client->getEmail() instanceof JsonSerializable);
    }
}
