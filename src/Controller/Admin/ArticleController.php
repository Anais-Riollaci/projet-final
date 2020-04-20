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
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    /**
     * @Route("/article/")
     */
    public function index(Request $request,
                          EntityManagerInterface $manager,
                          ArticleRepository $repository,
                          KernelInterface $appKernel)
    {
            $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $article->setCreatedAt(new \DateTime());


            $uploadFolder = $appKernel->getProjectDir() . "/public";
            $baseFolder = "/img/";

            /** @var UploadedFile $image */
            $image= $article->getPicture();

            $fileName = $image->getClientOriginalName();

            $image->move($uploadFolder . $baseFolder, $fileName);
            $article->setPicture($baseFolder . $fileName);

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

            ]);
    }

    /**
     * @Route("article/edit/{id}")
     */
    public function edit(Request $request,
                         ArticleRepository $articleRepository,
                         EntityManagerInterface  $manager,
                         $id,KernelInterface $appKernel)
    {
        $article = $articleRepository->find($id);
        $originalImage = null;

        if(!empty($article->getPicture())){
            $originalImage=$article->getPicture();
            $article->setPicture(
                new File($this->getParameter('upload_dir') . $article->getPicture())
            );
        }


        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!empty($article->getPicture())) {


                /** @var UploadedFile $image */
                $image = $article->getPicture();

                $fileName = $image->getClientOriginalName();

                $image->move($this->getParameter('upload_dir') , $fileName);
                $article->setPicture(  $fileName);
            }else{
                $article->setPicture($originalImage);
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('app_admin_article_index');
        }
        return $this->render('admin/modif_article.html.twig', [
            'form' => $form->createView(),
            'article' => $article->getId(),
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