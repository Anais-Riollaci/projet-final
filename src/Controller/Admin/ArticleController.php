<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{id}", defaults={"id": null}, requirements={"id": "\d+"})
     */
    public function index(Request $request, EntityManagerInterface $manager, ArticleRepository $repository,$id)
    {
        $originalImage = null;

        if (is_null($id)) {
            $article = new Article();
        } else {
            $article = $manager->find(Article::class, $id);

            if (is_null($article)) {
                throw new NotFoundHttpException();
            }
            if (!is_null($article->getPicture())) {
                $originalImage = $article->getPicture();

                $article->setPicture(
                    new File($this->getParameter('upload_dir') . $article->getPicture())
                );
            }
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $article->getPicture();
            $article->setCreatedAt(new \DateTime());


            if (!is_null($image)) {
                $filename = uniqid() . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('upload_dir'),
                    $filename
                );

                $article->setPicture($filename);
                if (!is_null($originalImage)) {
                    unlink($this->getParameter('upload_dir') . $originalImage);
                }
            } else {
                $article->setPicture($originalImage);
            }

            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success', "L'article est enregistré");

            return $this->redirectToRoute('app_admin_article_index');
        }
            $articles = $repository->findAll();

        return $this->render('admin/article.html.twig',
            [
            'form' => $form->createView(),
            'article' => $articles,
            'original_image' => $originalImage
            ]);
    }


    /**
     * @Route("/suppression/{id}")
     */
    public function delete( EntityManagerInterface $manager, Article $article)
    {
        $manager->remove($article);
        $manager->flush();

        $this->addFlash('success', 'Cette article est supprimé');

        return $this->redirectToRoute('app_admin_article_index');

    }
}