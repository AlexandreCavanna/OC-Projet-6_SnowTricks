<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use App\Service\FileUploader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TrickFixtures extends Fixture
{
    /**
     * @var FileUploader
     */
    private FileUploader $fileUploader;
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;
    /**
     * @var string
     */
    private string $targetDirectory;

    /**
     * @param string $targetDirectory
     * @param FileUploader $fileUploader
     * @param Filesystem $filesystem
     */
    public function __construct(string $targetDirectory, FileUploader $fileUploader, Filesystem $filesystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->fileUploader = $fileUploader;
        $this->filesystem = $filesystem;
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $this->filesystem->remove($this->targetDirectory);
        $group = ['Grabs', 'Rotations', 'Flips', 'Rotations désaxées', 'Slides'];
        $find1 = new Finder();
        $find1->in(__DIR__.'/coverImages')->name('/\.php|\.jpg|\.jpeg|\.png$/');

        $arrCoverImages = [];
        foreach ($find1 as $file) {
            $arrCoverImages[] = $file->getBasename();
        }

        $find2 = new Finder();
        $find2->in(__DIR__.'/pictures')->name('/\.php|\.jpg|\.jpeg|\.png$/');
        $arrPictures = [];
        foreach ($find2 as $file) {
            $arrPictures[] = $file->getBasename();
        }

        $arrVideos = [
            'https://www.youtube.com/embed/SFYYzy0UF-8',
            'https://www.youtube.com/embed/gbHU6J6PRRw',
            'https://www.youtube.com/embed/JGaZ_qctLvA',
        ];

        for ($i = 0; $i < 21; ++$i) {
            $keyGroup = array_rand($group);

            $keyCoverImages = array_rand($arrCoverImages);

            $trick = new Trick();
            $trick->setName($faker->word());
            $trick->setDescription($faker->text());
            $trick->setLabel($group[$keyGroup]);

            $fileCoverImage = new UploadedFile(
                __DIR__. '/coverImages/'.$arrCoverImages[$keyCoverImages],
                $arrCoverImages[$keyCoverImages]
            );

            $filename = $this->fileUploader->upload($fileCoverImage);

            $this->filesystem->copy(
                __DIR__.'/coverImages/'.$arrCoverImages[$keyCoverImages],
                $this->targetDirectory. '/coverImages/'.$filename
            );

            $trick->setCoverImage($filename);

            for ($j = 1; $j <= mt_rand(1, 3); ++$j) {
                $keyPictures = array_rand($arrPictures);
                $images = new Picture();

                $filePicture = new UploadedFile(
                    __DIR__. '/pictures/'.$arrPictures[$keyPictures],
                    $arrPictures[$keyPictures]
                );

                $filename = $this->fileUploader->upload($filePicture);

                $this->filesystem->copy(
                    __DIR__.'/pictures/'.$arrPictures[$keyPictures],
                    $this->targetDirectory. '/pictures/'.$filename
                );

                $images->setName($filename);
                $manager->persist($images);
                $trick->addPicture($images);
            }

            for ($j = 1; $j <= mt_rand(1, 3); ++$j) {
                $keyVideos = array_rand($arrVideos);
                $videos = new Video();
                $videos->setLink($arrVideos[$keyVideos]);
                $manager->persist($videos);
                $trick->addVideo($videos);
            }
            $manager->persist($trick);
        }
        $manager->flush();
    }
}
