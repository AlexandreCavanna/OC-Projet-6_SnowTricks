<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Manager\TrickManager;
use App\Repository\TrickRepository;
use App\Service\FileUploader;
use App\Service\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class TrickController extends AbstractController
{
    /**
     * @Route("/", name="trick_index", methods={"GET"})-
     */
    public function index(TrickRepository $trickRepository, Pagination $pagination): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->loadTricks($pagination->getOffset(1), Trick::LIMIT_PER_PAGE),
            'nextPage'  => 2
        ]);
    }

    /**
     * @Route("/tricks/pagination", name="trick_pagination", methods={"GET"})
     */
    public function loadMoreTricks(
        Request $request,
        TrickRepository $trickRepository,
        Environment $twig,
        Pagination $pagination
    ): Response {
        return new Response(
            json_encode([
                'html' => $twig->render(
                    'partials/_load_more_tricks.html.twig',
                    [
                        'tricks' => $trickRepository->loadTricks(
                            $pagination->getOffset($request->query->get('page')),
                            Trick::LIMIT_PER_PAGE
                        ),
                        'nextPage'  => 2
                    ]
                ),
                'pages' => $pagination->getPages($trickRepository)
            ]),
            200,
            ['Content-Type' => 'application/json']
        );
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
        FileUploader $fileUploader,
        TrickManager $trickManager
    ): Response {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickManager->handleCoverImage($trick, $form, $fileUploader);
            $trickManager->addPictures($trick, $form, $fileUploader);
            $trickManager->addVideos($trick, $form);

            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'La figure <strong>'.$trick->getName().'</strong> a été <strong>créé</strong> avec succès !'
            );

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
        FileUploader $fileUploader,
        TrickManager $trickManager
    ): Response {
        $coverImagePath = new File($this->getParameter('pictures_directory').'/coverImages/'.$trick->getCoverImage());

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $trickManager->handleCoverImage($trick, $form, $fileUploader, $coverImagePath);
            $trickManager->addPictures($trick, $form, $fileUploader);
            $trickManager->addVideos($trick, $form);

            $entityManager->flush();

            $this->addFlash(
                'success',
                'La figure <strong>'.$trick->getName().' </strong>a été <strong>modifié</strong> avec succès !'
            );

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
    public function delete(
        Request $request,
        Trick $trick,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        $this->addFlash(
            'success',
            'La figure <strong>'.$trick->getName().' </strong>a été <strong>supprimé</strong> avec succès !'
        );

        return $this->redirectToRoute('trick_index');
    }
}
