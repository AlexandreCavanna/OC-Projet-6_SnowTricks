<?php


namespace App\Manager;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class TrickManager
{
    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \App\Entity\Trick $trick
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function addVideos(Trick $trick, FormInterface $form, EntityManagerInterface $entityManager): void
    {
        $videos = $form->get('videos')->getData();

        if ($videos) {
            foreach ($videos as $vid) {
                if (str_contains($vid->getLink(), '.be')) {
                    $vid->setLink(substr_replace($vid->getLink(), 'be.com/embed', 13, 3));
                }

                $vid->setTrick($trick);
                $trick->addVideo($vid);
                $entityManager->persist($vid);
            }
        }
    }

    /**
     * @param \App\Entity\Trick $trick
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \App\Service\FileUploader $fileUploader
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function addPictures(Trick $trick, FormInterface $form, FileUploader $fileUploader, EntityManagerInterface $entityManager): void
    {
        $pictureImageFile = $form->get('pictures')->getData();
        if ($pictureImageFile) {
            foreach ($pictureImageFile as $pic) {
                $pictureFileName = $fileUploader->upload($pic);
                $picture = new Picture();
                $picture->setName($pictureFileName);
                $trick->addPicture($picture);
                $entityManager->persist($picture);
            }
        }
    }

    /**
     * @param \App\Entity\Trick $trick
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \App\Service\FileUploader $fileUploader
     * @param null $coverImagePath
     */
    public function handleCoverImage(Trick $trick, FormInterface $form, FileUploader $fileUploader, $coverImagePath = null): void
    {
        $coverImageFile = $form->get('coverImage')->getData();
        if ($coverImageFile) {
            $coverImageFileName = $fileUploader->upload($coverImageFile);
            $trick->setCoverImage($coverImageFileName);
        } else {
            $trick->setCoverImage(explode('/', $coverImagePath)[6]);
        }
    }
}
