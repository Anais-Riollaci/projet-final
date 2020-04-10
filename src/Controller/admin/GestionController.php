<?php

//correspond au fichier template html "gestion_profils",
// et donc à la gestion côtés Admin des fiches des animaux


namespace App\Controller\admin;


use App\Repository\CategoryRepository;
use App\Repository\RaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GestionController
 * @package App\Controller\admin
 *
 * @Route("/gestion/profil")
 */

// information pour la route plus générale, surement en le plaçant dans la section réservée aux Admin

class GestionController extends AbstractController
{
    public function gestionProfils(Request $request)
    {

        $profilsFromDb = $profilsRepository->findAll();
        $profils = [];
        foreach ($profilsFromDbFromDb as $item) {
            $profils[$item->getTitle()] = $item->getId();
        }

        return $this->render( 'gestion/GestionCategory.html.twig');

    }


    /**
     * @Route("/gestion/Race")
     */
    public function gestionRace(EntityManagerInterface $manager, RaceRepository $raceRepository){

        $raceFromDb = $raceRepository->findAll();
        $race = [];
        foreach ($raceFromDb as $item) {
            $race[$item->getTitle()] = $item->getId();
        }

        return $this->render( 'gestion/GestionCategory.html.twig');

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

        return $this->render( 'GestionCategory.html.twig');

    }
}