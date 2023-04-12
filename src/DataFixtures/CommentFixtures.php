<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(private UserRepository $userRepository, private TopicRepository $topicRepository){}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = $this->userRepository->findAll();
        $topics = $this->topicRepository->findAll();
        for ($i=0; $i < 10000; $i++) { 
            $user = $users[array_rand($users)];
            $topic = $topics[array_rand($topics)];
            $comment = new Comment();
            $comment
                ->setContent($faker->realText())
                ->setCreatedAt(new DateTimeImmutable())
                ->setTopic($topic)
                ->setAuthor($user)
            ;
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TopicFixtures::class
        ];
    }
}
