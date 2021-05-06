<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * Обработчик для главной страницы
     *
     * @Route("/", name="app")
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
            'posts' => $postRepository->findBy([], ['dateOfCreation' => 'DESC'], 4),
        ]);
    }
}
