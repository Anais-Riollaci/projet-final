<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment")
     */
    public function comment(Request $request,
                            EntityManagerInterface $manager,
                            CommentsRepository $repository
                            )

        //  ici se fait la création des avis
    {
        $comment = new Comments();
        $form = $this->createForm(CommentsType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $this->getUser();



            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Votre avis a été enregistré');


            // normalement, le route 'accueil devrait ramener vers l'accueil'
            return $this->redirectToRoute('app_comment_comment');

            }
            $comment = $repository->findAll();

        return $this->render('message/comments.html.twig', [
            'form' => $form->createView(),
            // création de formulaire avec le mot form
            'comment' => $comment,
        ]);
    }
}