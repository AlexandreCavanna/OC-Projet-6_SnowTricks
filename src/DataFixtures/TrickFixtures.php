<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $group = ['Grabs', 'Rotations', 'Flips', 'Rotations désaxées', 'Slides'];
        $arrPictures = ['snowboard-60ad7c340d935.jpg', 'snowboard-60ad7c340da9e.jpg', 'snowboard-60ad7c340da26.jpg'];
        $arrVideos = [
            'https://www.youtube.com/embed/SFYYzy0UF-8',
            'https://www.youtube.com/embed/gbHU6J6PRRw',
            'https://www.youtube.com/embed/JGaZ_qctLvA',
        ];

        for ($i = 0; $i < 21; ++$i) {
            $keyGroup = array_rand($group);
            $keyPictures = array_rand($arrPictures);

            $trick = new Trick();
            $trick->setName($faker->word());
            $trick->setDescription($faker->text());
            $trick->setLabel($group[$keyGroup]);
            $trick->setCoverImage($arrPictures[$keyPictures]);

            for ($j = 1; $j <= mt_rand(1, 3); ++$j) {
                $keyPictures = array_rand($arrPictures);
                $images = new Picture();
                $images->setName($arrPictures[$keyPictures]);
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
