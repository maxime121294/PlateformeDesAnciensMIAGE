<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Category;

class LoadCategroyData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $evenement = new Category();
        $evenement->setWording('Événement');
        $manager->persist($evenement);

        $emploi = new Category();
        $emploi->setWording('Offre d\'emploi d\'entreprises');
        $manager->persist($emploi);

        $cooptation = new Category();
        $cooptation->setWording('Offre de cooptation');
        $manager->persist($cooptation);

        $autre = new Category();
        $autre->setWording('Autre');
        $manager->persist($autre);

        $manager->flush();
    }
}