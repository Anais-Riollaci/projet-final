<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Animal;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function message(Request $request,
                            EntityManagerInterface $manager,
                            CommentsRepository $repository
                            // , Animal $animal
                            )

        // normalement, ici se fait la création des avis
    {
        $comment = new Message();
        $form = $this->createForm(MessageType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // if () dans le empty ($animal)

            $comment
                ->setUser($this->getUser())// ->setAnimal($animal)
            ;

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Votre avis a été enregistré');


            // normalement, le route 'accueil devrait ramener vers l'accueil'
            return $this->redirectToRoute('accueil');

        }  else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');

            }
            $comment = $repository->findAll();

        return $this->render('message/comments.html.twig', [
            'form' => $form->createView(),
            // création de formulaire avec le mot form
            'comment' => $comment
        ]);
    }
}