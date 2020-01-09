<?php

namespace App\DataFixtures;

use App\Entity\StoreType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class StoreTypeFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $types = ["Sport", "Clothing", "Library", "Pharmacy" , "Music" ];
        foreach($types as $type){

            $product = new StoreType();
            $product->setName($type);
            $manager->persist($product);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['store'] ;
    }
}
