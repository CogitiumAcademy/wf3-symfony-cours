<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/post/{id}', name: 'post_view', methods: ["GET"], requirements: ['id' => '\d+'])]
    public function post_view($id): Response
    {
        return $this->render('post/post.html.twig', [
            'post' => [
                'title' => 'Le titre',
                'content' => 'Le super contenu',
                'displayName' => 'Ph Giraud',
                'createdAt' => '07/11/2023 à 11h46'
            ]
        ]);
    }

}
