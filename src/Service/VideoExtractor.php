<?php


namespace App\Service;

use App\Entity\Video;

class VideoExtractor
{
    public function extractVideo(Video $videoLink, string $search, string $replace, int $offset, int $length): void
    {
        if (str_contains($videoLink->getLink(), $search)) {
            $videoLink->setLink(substr_replace($videoLink->getLink(), $replace, $offset, $length));
        }
    }
}
