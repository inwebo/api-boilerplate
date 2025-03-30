<?php

declare(strict_types=1);

namespace App\Person\DataFixtures;

use App\Person\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PersonFixtures extends Fixture implements FixtureGroupInterface
{
    private const int COUNT = 1;

    public static function getGroups(): array
    {
        return [
            'Person',
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        for ($i = 1; $i <= self::COUNT; ++$i) {
            $person = (new Person())->setFirstName($faker->firstName())->setLastName($faker->lastName());

            $manager->persist($person);
            $manager->flush();
        }
    }
}
