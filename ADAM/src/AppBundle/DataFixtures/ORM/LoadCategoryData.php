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
        $evenement->setwording('Événement');
        $manager->persist($evenement);

        $emploi = new Category();
        $emploi->setwording('Offre d\'emploi d\'entreprises');
        $manager->persist($emploi);

        $cooptation = new Category();
        $cooptation->setwording('Offre de cooptation');
        $manager->persist($cooptation);

        $autre = new Category();
        $autre->setwording('Autre');
        $manager->persist($autre);

        $manager->flush();
    }
}