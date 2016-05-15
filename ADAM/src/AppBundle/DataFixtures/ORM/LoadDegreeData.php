<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Degree;

class LoadDegreeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $licence = new Degree();
        $licence->setWording('Licence');

        $manager->persist($licence);

        $master = new Degree();
        $master->setWording('Master');

        $manager->persist($master);

        $manager->flush();
    }
}