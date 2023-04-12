<?php

namespace App\DataFixtures;

use App\Entity\Topic;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Migrations\Configuration\EntityManager\ManagerRegistryEntityManager;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TopicFixtures extends Fixture implements DependentFixtureInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $this->userRepository->findAll();

        for ($i=0; $i < 1000; $i++) {
            $topic = new Topic();
            $userRandomKey = array_rand($users);
            $user = $users[$userRandomKey];
            $topic
                ->setContent($faker->realText())
                ->setTitle($faker->realText(10))
                ->setCreatedAt(new DateTimeImmutable())
                ->setAuthor($user)
                ->setUpdatedAt(new DateTimeImmutable())
            ;
            $manager->persist($topic);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}

