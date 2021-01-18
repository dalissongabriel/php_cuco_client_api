<?php

namespace App\Tests\Unit;

use App\Entity\Client;
use JsonSerializable;
use PHPUnit\Framework\TestCase;

class CpfTest extends TestCase
{
    public function testShouldEnsureThatEntityIsSerializable()
    {
        $client = new Client();
        $client->setCpf("278.128.110-77");

        self::assertTrue($client->getCpf() instanceof JsonSerializable);
    }
}
