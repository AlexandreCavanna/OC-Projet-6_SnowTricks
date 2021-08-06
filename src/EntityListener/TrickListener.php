<?php


namespace App\EntityListener;

use App\Entity\Trick;
use App\Service\FileUploadedRemover;
use App\Service\Slugger;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PostRemove;
use Doctrine\ORM\Mapping\PrePersist;

class TrickListener
{
    private FileUploadedRemover $fileUploadedRemover;

    private Slugger $slugger;

    public function __construct(FileUploadedRemover $fileUploadedRemover, Slugger $slugger)
    {
        $this->fileUploadedRemover = $fileUploadedRemover;
        $this->slugger = $slugger;
    }

    /** @PrePersist */
    public function prePersistHandler(Trick $trick)
    {
        $trick->setSlug($this->slugger->slugify($trick->getName()));
    }

    /** @ORM\PreUpdate() */
    public function preUpdateHandler(Trick $trick)
    {
        $trick->setSlug($this->slugger->slugify($trick->getName()));
        $trick->setModifyAt(new \DateTimeImmutable());
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
