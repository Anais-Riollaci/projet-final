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
     * @Route("/user")
     */
    public function show( EntityManagerInterface $manager, UserRepository $repository)
    {
        $users = $repository->findAll();
        return $this->render('admin/user.html.twig', [
            'user' => $users
        ]);
    }
}