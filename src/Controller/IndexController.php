<?php


namespace App\Controller;

use App\Entity\Comments;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(
        CategoryRepository $categoryRepository,
        ArticleRepository $repository,
        CommentsRepository $commentsRepository
    )
    {
        $categories = $categoryRepository->findAll();

        $articles = $repository->findBy(
            [],
            ['createdAt' => 'DESC'],
            5
        );

        $comments = $commentsRepository->findBy(
            [],
            ['createAt' => 'DESC'],
            3
        );

        $repo = $this->getDoctrine()->getRepository(Comments::class);

        return $this->render(
            'index/index.html.twig',
            [
                'articles' => $articles,
                'categories' => $categories,
                'comments' => $comments
            ]
        );
    }

}