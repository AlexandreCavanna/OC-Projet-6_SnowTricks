<?php


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 20; ++$i) {
            $user = new User();
            $user->setRoles(['ROLE_USER', 'TRICK_EDIT', 'TRICK_DELETE', 'TRICK_NEW']);
            $user->setEmail($faker->email);
            $user->setPseudo($faker->userName);
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                'password'
            ));
            $manager->persist($user);
            $this->addReference(User::class.'_'.$i, $user);
        }
        $manager->flush();
    }
}
