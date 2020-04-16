<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(ArticleRepository $repository)
    {
        $articles = $repository->findBy(
            [],
            ['createdAt' => 'DESC'],
            5
        );

        return $this->render(
            'index/index.html.twig',
            [
                'articles' => $articles
            ]
        );
    }

}