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
     * @var \App\Service\FileUploader
     */
    private FileUploader $fileUploader;

    /**
     * @param EntityManagerInterface $entityManager
     * @param \App\Service\FileUploader $fileUploader
     */
    public function __construct(EntityManagerInterface $entityManager, FileUploader $fileUploader)
    {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
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
     */
    public function addPictures(Trick $trick, FormInterface $form): void
    {
        $pictureImageFile = $form->get('pictures')->getData();
        if ($pictureImageFile) {
            foreach ($pictureImageFile as $pic) {
                $pictureFileName = $this->fileUploader->upload($pic, 'pictures');
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
     * @param null $coverImagePath
     */
    public function handleCoverImage(Trick $trick, FormInterface $form, $coverImagePath = null): void
    {
        $coverImageFile = $form->get('coverImage')->getData();
        if ($coverImageFile) {
            $coverImageFileName = $this->fileUploader->upload($coverImageFile, 'coverImages');
            $trick->setCoverImage($coverImageFileName);
        } else {
            $coverImagePathExplode = explode('/', $coverImagePath);
            $trick->setCoverImage(end($coverImagePathExplode));
        }
    }

    public function addUser(Trick $trick, $user): Trick
    {
        return $trick->setUser($user);
    }

    /**
     * @param Trick $trick
     * @param $user
     * @param FormInterface $form
     */
    public function create(Trick $trick, $user, FormInterface $form): void
    {
        $this->addUser($trick, $user);
        $this->handleCoverImage($trick, $form);
        $this->addPictures($trick, $form);
        $this->addVideos($trick, $form);

        $this->entityManager->persist($trick);
        $this->entityManager->flush();
    }

    /**
     * @param Trick $trick
     * @param FormInterface $form
     * @param string $coverImagePath
     */
    public function edit(Trick $trick, FormInterface $form, string $coverImagePath): void
    {
        $this->handleCoverImage($trick, $form, $coverImagePath);
        $this->addPictures($trick, $form);
        $this->addVideos($trick, $form);

        $this->entityManager->flush();
    }
}
