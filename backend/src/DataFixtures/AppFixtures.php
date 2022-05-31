<?php

namespace App\DataFixtures;

use App\Entity\ProductCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('ru_RU');
        
        foreach (range(1, 100) as $_) {
            $category = new ProductCategory();
            $category->setTitle($faker->company);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
