<?php

//correspond au fichier template html "gestion_profils",
// et donc à la gestion côtés Admin des fiches des animaux


namespace App\Controller\admin;


use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GestionController
 * @package App\Controller\admin
 *
 * @Route("/")
 */

// information pour la route plus générale, surement en le plaçant dans la section réservée aux Admin

class GestionController extends AbstractController
{
    public function gestionprofils(Request $request)
    {

    }


    /**
     * @Route("/gestion/category")
     */
    public function gestionCategory(EntityManagerInterface $manager,CategoryRepository $categoryRepository){

        $categoryFromDb = $categoryRepository->findAll();
        $category = [];
        foreach ($categoryFromDb as $item) {
            $category[$item->getTitle()] = $item->getId();
        }

        return $this->render( 'gestion/GestionCategory.html.twig');

    }
}