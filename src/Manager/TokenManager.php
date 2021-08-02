<?php


namespace App\Manager;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class TokenManager
{
    private EntityManagerInterface $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \App\Entity\User $user
     * @param \Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface $tokenGenerator
     */
    public function create(User $user, TokenGeneratorInterface $tokenGenerator): void
    {
        if ($user->getToken() === null) {
            $token = new Token();

            $token->setToken($tokenGenerator->generateToken());
            $this->entityManager->persist($token);

            $user->setToken($token);
            $this->entityManager->persist($user);
        }
        $user->getToken()->setToken($tokenGenerator->generateToken());
        $user->getToken()->setDate(new \DateTimeImmutable());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
