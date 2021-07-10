<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function loadComments(int $offset, int $limit, Trick $trick)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.trick = :trick')
            ->setParameter('trick', $trick)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery();

        $qb->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getResult();
    }
}
