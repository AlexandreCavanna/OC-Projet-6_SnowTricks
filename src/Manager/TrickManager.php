<?php


namespace App\Manager;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class TrickManager
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormInterface $form
     * @param Trick $trick
     */
    public function addVideos(Trick $trick, FormInterface $form): void
    {
        $videos = $form->get('videos')->getData();

        if ($videos) {
            foreach ($videos as $vid) {
                if (str_contains($vid->getLink(), '.be')) {
                    $vid->setLink(substr_replace($vid->getLink(), 'be.com/embed', 13, 3));
                }

                $vid->setTrick($trick);
                $trick->addVideo($vid);
                $this->entityManager->persist($vid);
            }
        }
    }

    /**
     * @param Trick $trick
     * @param FormInterface $form
     * @param FileUploader $fileUploader
     */
    public function addPictures(Trick $trick, FormInterface $form, FileUploader $fileUploader): void
    {
        $pictureImageFile = $form->get('pictures')->getData();
        if ($pictureImageFile) {
            foreach ($pictureImageFile as $pic) {
                $pictureFileName = $fileUploader->upload($pic);
                $picture = new Picture();
                $picture->setName($pictureFileName);
                $trick->addPicture($picture);
                $this->entityManager->persist($picture);
            }
        }
    }

    /**
     * @param Trick $trick
     * @param FormInterface $form
     * @param FileUploader $fileUploader
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
