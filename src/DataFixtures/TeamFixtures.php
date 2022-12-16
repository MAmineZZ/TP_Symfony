<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {
            $entity = new Team();
            $entity
                ->setName("team$i");
                //mise en memoire de l'entité pour y accéder dans player fixtures

            $this->addReference("refTeam$i",$entity);

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
