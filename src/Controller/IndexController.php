<?php


namespace App\Controller;

use App\Form\CommentsType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;

// use Symfony\Component\HttpFoundation\Request;
// use Doctrine\Common\Persistence\ObjectManager;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Comments;

// use App\Repository\CommentsRepository;

class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(CategoryRepository $categoryRepository, ArticleRepository $repository)
    {
        $categories = $categoryRepository->findAll();

        $articles = $repository->findBy(
            [],
            ['createdAt' => 'DESC'],
            5
        );

        $comments = $repository->findBy(
            [],
            ['createdAt' => 'DESC'],
            3
        );

        $repo = $this->getDoctrine()->getRepository(Comments::class);

        $content = $repo->findAll();

        return $this->render(
            'index/index.html.twig',
            [
                'articles' => $articles,
                'categories' => $categories,
                'comments' => $comments,
                $content = $repo->findAll()
            ]
        );
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/message/new", name="comments_create")
     */
    public function create()
    {

        $comment = new Comments();

        $form = $this->createForm(CommentsType::class, $comment);

        return $this->render('edit.html.twig', [
            'formComment' => $form->createView()
        ]);
    }




// un rajout du Mercredi 15 Avril
// pour placer les commentaires de témoignages, je mets de côté la section messages pour le moment
// message.html.twig

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/message/", name="comments_show")
     */
    public function show()
    {
        $repo = $this->getDoctrine()->getRepository(Comments::class);

        $comment = $repo->findAll();

        return $this->render('message/comments.html.twig', [
                'comments' => $comment
            ]
        );
    }

}