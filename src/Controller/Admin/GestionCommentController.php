<?php

namespace App\Controller\Admin;

use App\Entity\Comments;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GestionCommentController extends AbstractController
{
    /**
     * @Route("/admin/comment", name="gestion_comment")
     */
    public function show(EntityManagerInterface $entityManager, CommentsRepository $repository)
    {
        $comments = $repository->findAll();
        return $this->render('admin/gestionComment.html.twig', [
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/admin/comment/{id}", name="delete_comment")
     */
    public function delete(EntityManagerInterface $entityManager, Comments $comments)
    {
        $entityManager->remove($comments);
        $entityManager->flush();

        $this->addFlash('success', 'Cet avis a été supprimé');

        return $this->redirectToRoute('gestion_comment');

    }
}
