<?php


namespace App\Service;

use App\Entity\Trick;
use App\Repository\TrickRepository;

class Pagination
{
    public function getOffset(int $page): int
    {
        return is_null($page) || $page === 1 ? 0 : ($page - 1) * Trick::LIMIT_PER_PAGE;
    }

    public function getPages(TrickRepository $trickRepository): int
    {
        $total = $trickRepository->count([]);

        return ceil($total / Trick::LIMIT_PER_PAGE);
    }
}
