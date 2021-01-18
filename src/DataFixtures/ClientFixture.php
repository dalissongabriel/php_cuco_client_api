<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $client1 = new Client();
        $client1->setName("Client Test 1");
        $client1->setCpf("446.069.350-06");
        $client1->setEmail("fructuosa2658@uorak.com");

        $manager->persist($client1);
        $manager->flush();

        $client2 = new Client();
        $client2->setName("Client Test 2");
        $client2->setCpf("949.487.580-00");
        $client2->setEmail("abdennacer2076@uorak.com");

        $manager->persist($client2);
        $manager->flush();
    }
}
