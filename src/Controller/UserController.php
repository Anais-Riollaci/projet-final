<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    /**
     * @Route("/inscription", name="registration")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user_login');
        }

        return $this->render('user/inscription.html.twig', [
            'controller_name' => 'UserController',

            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/connexion" , name="user_login")
     */
    public function login(AuthenticationUtils $authenticationUtils){

        $error = $authenticationUtils->getLastAuthenticationError();
        dump($error);

        $lastUsername = $authenticationUtils->getLastUsername();

        if (!empty($error)) {
            $this->addFlash('error', 'Identifiant ou mot de passe incorrects');

        }
        return $this->render('user/login.html.twig',
            [
                'last_username' => $lastUsername
            ]);
    }

    /**
     * @Route("/deconnexion", name="user_logout")
     */
    public function logout(){}
}
