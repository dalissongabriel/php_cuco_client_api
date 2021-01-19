<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername("user_cuco_api")
            ->setPassword('$argon2i$v=19$m=65536,t=4,p=1$d1cub3hiMlVPbG5lUlR3Qg$/C+R1qfuFeDXXlUw1005fT0ev3Ok4fF1UUzDZXIEgwk');
        $manager->persist($user);
        $manager->flush();
    }
}