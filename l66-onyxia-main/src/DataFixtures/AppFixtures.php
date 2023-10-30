<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $usersData = [
            1 => ['email' => 'test@test.pl', 'password' => 'test@test.pl', 'roles' => ['']],
            2 => ['email' => 'admin@l66.pl', 'password' => 'admin@l66.pl', 'roles' => ['ROLE_ADMIN']]
        ];

        UserFactory::createMany(count($usersData), function (int $i) use ($usersData) {
            return [
                'email' => $usersData[$i]['email'],
                'password' => $usersData[$i]['password'],
                'roles' => $usersData[$i]['roles']
            ];
        });

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
