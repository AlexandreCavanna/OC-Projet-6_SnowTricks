<?php


namespace App\Service;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class Pagination
{
    public function getOffset(int $page, int $limit): int
    {
        return is_null($page) || $page === 1 ? 0 : ($page - 1) * $limit;
    }

    public function getPages(ServiceEntityRepository $serviceEntityRepository1, int $limit, Trick $trick = null): int
    {
        if (null !== $trick) {
            $comments = $trick->getComments();
            $total = $comments->count();
        } else {
            $total = $serviceEntityRepository1->count([]);
        }

        return ceil($total /  $limit);
    }
}
