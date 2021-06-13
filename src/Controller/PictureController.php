<?php

namespace App\Controller;

use App\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{
    /**
     * @Route("/picture/{id}/delete", name="picture_delete", methods={"DELETE"})
     */
    public function delete(Picture $picture, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($picture);
        $entityManager->flush();

        return $this->json(['code' => 200, 'message' => 'Image supprimée'], 200)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
