<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Screen;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ScreenFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->createScreen1($manager);
        $this->createScreen2($manager);
        $manager->flush();
    }

    public function createScreen1(ObjectManager $manager)
    {
        $screen = new Screen();
        $screen->setName('Screen 1');
        $screen->setFirstConnect(new \DateTime());
        $screen->setLastConnect(new \DateTime());
        $screen->setAdminC('My Admin-C');
        $screen->setGuid('b8f222ef-a1a5-4cd1-a3ef-d775ec0e9505');
        $screen->setConnectCode('1');
        $screen->setLocation('My location');

        $manager->persist($screen);
    }

    public function createScreen2(ObjectManager $manager)
    {
        $screen = new Screen();
        $screen->setName('Screen 2');
        $screen->setFirstConnect(new \DateTime());
        $screen->setLastConnect(new \DateTime());
        $screen->setAdminC('My Admin-C');
        $screen->setGuid('e0103d3e-89fa-4bda-a5e6-0871b6773a0b');
        $screen->setConnectCode('2');
        $screen->setLocation('My location');

        $manager->persist($screen);
    }
}
