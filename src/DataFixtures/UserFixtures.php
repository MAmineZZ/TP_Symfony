<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //creation d'un user avec role ROLE_USER
        $user = new User();
        $user->setEmail('user@user.com');
        $user->setPassword('$2y$13$BqSaNDrvbNi7ft7Hx7u6IetVl7uHt/dMrAxFNBBFaAGhHrbm8KWD2');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);


        //creation d'un user avec role ROLE_ADMIN
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setPassword('$2y$13$fBqh7AnWtQCo6GV1De2GV.jIWRCwshWqqmD2jiO.tntJJK/3D72BS');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);


        $manager->flush();
    }
}
