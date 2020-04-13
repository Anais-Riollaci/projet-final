<?php

namespace App\Controller\Admin;


use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

class GestionNewsController extends AbstractController
{
    /**
     * @Route("/new_article", name="news_article")
     */
    public function new(Request $request,
                        EntityManagerInterface $manager, $id)
    {
        $originalImage = null;

        if (is_null($id)) { // création
            $article = new Article();
            $article->setAuthor($this->getUser());
        } else { // modification
            $article = $manager->find(News::class);
            if (is_null($article)) {
                throw new NotFoundHttpException();
            }
            if (!is_null($article->getImage())) {
                // nom du fichier venant de la bdd
                $originalImage = $article->getImage();
                // le champ de formulaire attend un objet File
                $article->setImage(
                    new File($this->getParameter('upload_dir') . $article->getImage())
                );
            }
        }

        $form = $this->createForm(NewsType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $image */
            $image = $article->getImage();

            // si une image a été uploadée
            if (!is_null($image)) {
                // nom sous lequel on va enregistrer l'image
                $filename = uniqid() . '.' . $image->guessExtension();
                // déplacement de l'image uploadée
                $image->move(
                // dans quel répertoire
                // cf config/services.yaml
                    $this->getParameter('upload_dir'),
                    // nom du fichier
                    $filename
                );
                // pour enregistrer le nom du fichier en bdd
                $article->setImage($filename);
                // suppression de l'ancienne image en modification s'il y en a une
                if (!is_null($originalImage)) {
                    unlink($this->getParameter('upload_dir') . $originalImage);
                }
            } else {
                // pour la modification, sans upload,
                // on remet le nom de l'image venant de la bdd
                $article->setImage($originalImage);
            }

            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success', "L'article est enregistré");


            return $this->render('index/index.html.twig', [
                'form' => $form->createView(),
                'original_image' => $originalImage
            ]);
        }
    }
    /**
     * @Route("/suppression/{id}", requirements={"id": "\d+"})
     */
    public function delete(EntityManagerInterface $manager, News $article)
    {
        // suppression de l'image si l'article en a une
        if (!is_null($article->getImage())) {
            $image = $this->getParameter('upload_dir') . $article->getImage();
            if (file_exists($image)) {
                unlink($image);
            }
        }
        // suppression en bdd
        $manager->remove($article);
        $manager->flush();

        $this->addFlash('success', "L'article est supprimé");

        return $this->redirectToRoute('new_article');
    }
}

