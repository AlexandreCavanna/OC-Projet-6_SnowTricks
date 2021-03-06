<?php


namespace App\Manager;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class CommentManager
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Trick $trick, User $user, FormInterface $form)
    {
        $comment = new Comment();
        $comment->setContent($form->get('content')->getData());
        $comment->setUser($user);
        $comment->setCreatedAt(new \DateTime());
        $comment->setTrick($trick);
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }
}
