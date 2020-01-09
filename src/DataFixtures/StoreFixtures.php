<?php
/**
 * Created by PhpStorm.
 * User: sayya
 * Date: 08/01/2020
 * Time: 11:27
 */

namespace App\DataFixtures;


use App\Entity\Store;
use App\Entity\StoreType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class StoreFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{




    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(ObjectManager $manager)
    {
        $store_type_repository  = $manager->getRepository(StoreType::class);
        $store_types = $store_type_repository->findAll();
        for($i = 0 ; $i < 20 ; $i++){
            $store = new Store();
            $store->setName("Store " . $i);
            /*
             * Generating random locations
             */
            $store->setLatitude((rand(0, 1000000) / 1000000) * 90 ) ;
            $store->setLongitude((rand(0, 1000000) / 1000000) * 180 )  ;
            $store->setType($store_types[rand(0,count($store_types) -1 )]) ;

            $manager->persist($store);

        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [StoreTypeFixtures::class] ;
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to
     *
     * @return string[]
     */
    public static function getGroups(): array
    {

        return ["store"] ;
    }
}