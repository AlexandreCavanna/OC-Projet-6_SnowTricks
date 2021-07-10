<?php


namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Repository\CommentRepository;
use App\Service\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CommentController extends AbstractController
{
    /**
     * @Route("/comments/pagination/{id}", name="comment_pagination", methods={"GET"})
     */
    public function loadMoreComments(
        Request $request,
        CommentRepository $commentRepository,
        Environment $twig,
        Trick $trick,
        Pagination $pagination
    ): Response {
        return new Response(
            json_encode([
                'html' => $twig->render(
                    'partials/_load_more_comments.html.twig',
                    [
                        'comments' => $commentRepository->loadComments(
                            $pagination->getOffset($request->query->get('page'), 10),
                            Comment::LIMIT_PER_PAGE,
                            $trick
                        ),
                        'nextPage'  => 2
                    ]
                ),
                'pages' => $pagination->getPages($commentRepository, 10, $trick)
            ]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
