<?php

namespace App\DataFixtures;

use App\Entity\Nationalteam;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NationalTeamFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 8; $i++) {
            $entity = new NationalTeam();

            $entity ->setFlag("image$i.jpeg");
            $entity
                ->setName("natteam$i");
            //mise en memoire de l'entité pour y accéder dans player fixtures

            $this->addReference("refnameteam$i",$entity);

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
