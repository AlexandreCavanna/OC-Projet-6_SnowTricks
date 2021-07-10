<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Comment::class, 500, function (Comment $comment) {
            $comment->setContent($this->faker->paragraph(mt_rand(1, 3)));
            $comment->setCreatedAt($this->faker->dateTime);
            $comment->setTrick($this->getRandomReference(Trick::class));
            $comment->setUser($this->getRandomReference(User::class));
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [TrickFixtures::class];
    }
}
