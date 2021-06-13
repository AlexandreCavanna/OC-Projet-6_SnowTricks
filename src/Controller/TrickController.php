<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("/", name="trick_index", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    /**
     * @Route("trick/{id}", name="trick_show", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function show(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("trick/new", name="trick_new", methods={"GET","POST"})
     */
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader
    ): Response {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coverImageFile = $form->get('coverImage')->getData();
            if ($coverImageFile) {
                $coverImageFileName = $fileUploader->upload($coverImageFile);
                $trick->setCoverImage($coverImageFileName);
            }

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

            $entityManager->persist($trick);
            $entityManager->flush();
            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("trick/{id}/edit", name="trick_edit", methods={"GET","POST"})
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        Trick $trick,
        FileUploader $fileUploader
    ): Response {
        $coverImagePath = new File($this->getParameter('pictures_directory').'/'.$trick->getCoverImage());

        $trick->setCoverImage(explode('/', $coverImagePath)[6]);

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
            $entityManager->flush();


            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("trick/{id}", name="trick_delete", methods={"POST"})
     */
    public function delete(Request $request, Trick $trick, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trick_index');
    }
}
