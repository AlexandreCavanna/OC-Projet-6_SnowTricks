<?php


namespace App\DataFixtures;

use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class UserFixtures extends Fixture
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 20; ++$i) {
            $user = new User();
            $user->setRoles(['ROLE_USER', 'TRICK_EDIT', 'TRICK_DELETE', 'TRICK_NEW']);
            $user->setEmail($faker->email);
            $user->setPseudo($faker->userName);
            $this->userManager->updatePassword($user);
            $manager->persist($user);
            $this->addReference(User::class.'_'.$i, $user);
        }
        $manager->flush();
    }
}
