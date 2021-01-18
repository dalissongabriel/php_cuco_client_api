<?php

namespace App\Tests\Unit;

use App\Entity\Client;
use JsonSerializable;
use PHPUnit\Framework\TestCase;

class PhoneTest extends TestCase
{
    public function testShouldEnsureThatEntityIsSerializable()
    {
        $client = new Client();
        $client->setPhone("(40) 404044040");

        self::assertTrue($client->getPhone() instanceof JsonSerializable);
    }
}
