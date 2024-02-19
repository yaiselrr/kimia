<?php

namespace App\DataFixtures;

use App\Entity\Position;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $positionArray = ['goalkeeper','midfield','defender','forward'];
        
        for ($i=0; $i < count($positionArray); $i++) { 
            $position = new Position();

            $position->setName($positionArray[$i]);

            $manager->persist($position);
        }

        $manager->flush();
    }
}
