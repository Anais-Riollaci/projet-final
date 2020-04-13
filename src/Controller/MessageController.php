<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Animal;
use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Animal $animal
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function message(  Request $request,
                              EntityManagerInterface $manager,
                              Animal $animal
    )
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($animal)) {
                $message
                    ->setUser($this->getUser())
                    ->setAnimal($animal);

                $manager->persist($message);
                $manager->flush();

                $this->addFlash('success', 'Votre message est enregistré');

                return $this->redirectToRoute('accueil');
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
            'animal' => $animal->getId(),
        ]);
    }


}
