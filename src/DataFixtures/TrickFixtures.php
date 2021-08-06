<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use App\Service\FileUploader;
use App\Service\Slugger;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TrickFixtures extends BaseFixture
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
     * @var Slugger
     */
    private Slugger $slugger;

    /**
     * @param string $targetDirectory
     * @param FileUploader $fileUploader
     * @param Filesystem $filesystem
     * @param Slugger $slugger
     */
    public function __construct(
        string $targetDirectory,
        FileUploader $fileUploader,
        Filesystem $filesystem,
        Slugger $slugger
    )
    {
        $this->targetDirectory = $targetDirectory;
        $this->fileUploader = $fileUploader;
        $this->filesystem = $filesystem;
        $this->slugger = $slugger;
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     * @throws \Exception
     */
    protected function loadData(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $this->filesystem->remove($this->targetDirectory.'/coverImages');
        $this->filesystem->remove($this->targetDirectory.'/pictures');
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

            $sentence = $faker->sentence(3);
            $trick->setName($sentence);
            $trick->setSlug($this->slugger->slugify($sentence));
            $trick->setDescription($faker->text());
            $trick->setLabel($group[$keyGroup]);
            $trick->setUser($this->getRandomReference(User::class));
            $trick->setCreatedAt($faker->dateTime);

            $this->filesystem->copy(
                __DIR__.'/coverImages/'.$arrCoverImages[$keyCoverImages],
                __DIR__.'/coverImages/tmp.jpg'
            );
            $fileCoverImage = new UploadedFile(
                __DIR__.'/coverImages/tmp.jpg',
                $arrCoverImages[$keyCoverImages],
                null,
                null,
                true
            );

            $filename = $this->fileUploader->upload($fileCoverImage, '/coverImages');


            $trick->setCoverImage($filename);

            for ($j = 1; $j <= mt_rand(1, 3); ++$j) {
                $keyPictures = array_rand($arrPictures);
                $images = new Picture();

                $this->filesystem->copy(
                    __DIR__.'/pictures/'.$arrPictures[$keyPictures],
                    __DIR__.'/pictures/tmp.jpg'
                );

                $filePicture = new UploadedFile(
                    __DIR__.'/pictures/tmp.jpg',
                    $arrPictures[$keyPictures],
                    null,
                    null,
                    true
                );

                $filename = $this->fileUploader->upload($filePicture, '/pictures');

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
            $this->addReference(Trick::class.'_'.$i, $trick);
        }
        $manager->flush();
    }
}
