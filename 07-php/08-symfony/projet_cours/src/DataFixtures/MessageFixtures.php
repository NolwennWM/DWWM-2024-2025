<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Message;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MessageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();
        $categories = [null];

        for ($i=0; $i < 10 ; $i++) 
        { 
            $category = new Category();
            $category->setName($faker->word());
            $manager->persist($category);
            $categories[] = $category;
        }

        for ($i = 0; $i < 30; $i++) 
        {
            $message = new Message();
            $message->setContent($faker->realTextBetween(50, 500))
                    ->setCategory($categories[array_rand($categories)])
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeThisDecade()));
            $manager->persist($message);
        }
        $manager->flush();
    }
}
