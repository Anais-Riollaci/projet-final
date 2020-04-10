<?php

//correspond au fichier template html "gestion_profils",
// et donc à la gestion côtés Admin des fiches des animaux


namespace App\Controller;



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



}