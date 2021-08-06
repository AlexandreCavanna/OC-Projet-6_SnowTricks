<?php


namespace App\Manager;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordEncoder;

    /**
     *
     */
    private const PASSWORD_FIELD = 'password';

    /**
     * UpdatePassword constructor.
     * @param UserPasswordHasherInterface $passwordEncoder
     */
    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User $user
     * @param FormInterface|null $form
     */
    public function updatePassword(User $user, FormInterface $form = null): void
    {
        if (null !== $form) {
            $formPassword = $form->get(self::PASSWORD_FIELD)->getData();
        }

        $user->setPassword(
            $this->passwordEncoder->hashPassword(
                $user,
                $formPassword ?? self::PASSWORD_FIELD
            )
        );
    }
}
