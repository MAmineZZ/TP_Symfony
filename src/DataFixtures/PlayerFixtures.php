<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\Team;
use App\Entity\Nationalteam;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PlayerFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        // TODO: Implement getOrder() method.
        return [
            Team::class,
            Nationalteam::class,
        ];
    }




    public function load(ObjectManager $manager): void
    {
		for ($i = 0; $i < 20; $i++) { 
			$entity = new Player();
			$entity
				->setFirstname("firstname$i")
				->setLastname("lastname$i")
				->setNumber( random_int(1, 11) )
				->setPortrait("image$i.jpeg")
				->setBirthday( new DateTime('2000-01-01') )

			;

            //recuperation des references de team crées dans TeamFixtures
            $entity->setTeam(
                $this->getReference("refTeam" . random_int(0,4))
            );

            $entity->setNationalTeam(
                $this->getReference("refnameteam". random_int(0,4))
            );


            $manager->persist($entity);
		}

        $manager->flush();
    }


}
