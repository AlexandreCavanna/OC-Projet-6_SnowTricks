<?php


namespace App\EntityListener;

use App\Entity\Trick;
use App\Service\FileUploadedRemover;
use Doctrine\ORM\Mapping\PostRemove;

class TrickListener
{
    private FileUploadedRemover $fileUploadedRemover;

    public function __construct(FileUploadedRemover $fileUploadedRemover)
    {
        $this->fileUploadedRemover = $fileUploadedRemover;
    }

    /** @PostRemove */
    public function postRemoveHandler(Trick $trick)
    {
        $this->fileUploadedRemover->removeUploadedFile($trick->getCoverImage());
        foreach ($trick->getPictures() as $picture) {
            $this->fileUploadedRemover->removeUploadedFile($picture->getName());
        }
    }
}
