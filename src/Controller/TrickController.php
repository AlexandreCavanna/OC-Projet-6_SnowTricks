<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Manager\CommentManager;
use App\Manager\TrickManager;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Service\Pagination;
use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TrickController extends AbstractController
{
    /**
     * @Route("/", name="trick_index", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository, Pagination $pagination, Slugger $slugger): Response
    {
        $tricks = $trickRepository->loadTricks(
            $pagination->getOffset(
                1,
                Trick::LIMIT_PER_PAGE
            ),
            Trick::LIMIT_PER_PAGE
        );

        $slugs = [];
        foreach ($tricks as $trick) {
            $slugs[] = $slugger->slugify($trick->getDescription());
        };

        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->loadTricks(
                $pagination->getOffset(
                    1,
                    Trick::LIMIT_PER_PAGE
                ),
                Trick::LIMIT_PER_PAGE
            ),
            'nextPage'  => 2,
            'slugs' => $slugs
        ]);
    }

    /**
     * @Route("/tricks/pagination", name="trick_pagination", methods={"GET"})
     */
    public function loadMoreTricks(
        Request $request,
        TrickRepository $trickRepository,
        Environment $twig
    ): Response {
        $pagination = new Pagination();
        return new Response(
            json_encode([
                'html' => $twig->render(
                    'partials/_load_more_tricks.html.twig',
                    [
                        'tricks' => $trickRepository->loadTricks(
                            $pagination->getOffset($request->query->get('page'), Trick::LIMIT_PER_PAGE),
                            Trick::LIMIT_PER_PAGE
                        ),
                        'nextPage'  => 2
                    ]
                ),
                'pages' => $pagination->getPages($trickRepository, Trick::LIMIT_PER_PAGE)
            ]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("trick/{id}/edit", name="trick_edit", methods={"GET","POST"})
     * @IsGranted("TRICK_EDIT", subject="trick")
     */
    public function edit(
        Request $request,
        Trick $trick,
        TrickManager $trickManager
    ): Response {
        if ($trick->getCoverImage() === 'trick-placeholder.jpg') {
            $coverImagePath = new File($this->getParameter('pictures_directory').'/placeholder/'.$trick->getCoverImage());
        } else {
            $coverImagePath = new File($this->getParameter('pictures_directory').'/coverImages/'.$trick->getCoverImage());
        }

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickManager->edit($form->getData(), $form, $coverImagePath);

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
     * @Route("trick/{id}/{slug}", name="trick_show", requirements={"id":"\d+"}, methods={"GET","POST"})
     */
    public function show(Trick $trick, Request $request, CommentManager $commentManager, CommentRepository $commentRepository, string $slug, Slugger $slugger): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentManager->create($trick, $user, $form);

            $this->addFlash(
                'success',
                'Le commentaire a été <strong>créé</strong> avec succès !'
            );

            return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'user' => $user,
            'comments' => $commentRepository->findBy(['trick' => $trick->getId()], ['createdAt' => 'DESC'], 10),
            'nextPage'  => 2,
            'form' => $form->createView(),
            'slug' => $slugger->slugify($slug)
        ]);
    }

    /**
     * @Route("trick/new", name="trick_new", methods={"GET","POST"})
     * @IsGranted("TRICK_NEW")
     */
    public function new(
        Request $request,
        TrickManager $trickManager
    ): Response {
        $form = $this->createForm(TrickType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickManager->create($form->getData(), $this->getUser(), $form);
            $this->addFlash(
                'success',
                'La figure <strong>'.$form->getData()->getName().'</strong> a été <strong>créé</strong> avec succès !'
            );

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("trick/delete/{id}", name="trick_delete", methods={"POST"})
     * @IsGranted("TRICK_DELETE", subject="trick")
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
