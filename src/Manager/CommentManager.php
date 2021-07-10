<?php


namespace App\Manager;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;

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

    public function create(Trick $trick, $user, $form)
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
