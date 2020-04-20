<?php


namespace App\Controller\Admin;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class UserAdminController extends AbstractController
{
    /**
     * @Route("/gestion/user")
     */
    public function show( EntityManagerInterface $manager, UserRepository $repository)
    {
        $users = $repository->findAll();
        return $this->render('admin/user.html.twig', [
            'user' => $users
        ]);
    }

    /**
     * @Route("/user/suppression/{id}")
     */
    public function delete( EntityManagerInterface $manager, User $user)
    {
        $manager->remove($user);
        $manager->flush();

        $this->addFlash('success', 'Cet utilisateur est supprimé');

        return $this->redirectToRoute('app_admin_useradmin_show');

    }
}