<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render(
            'test/index.html.twig', 
            [
                'ma_case' => 'TestController',
            ]
        );
        //return $this->json(['username' => 'jane.doe']);
    }

    #[Route('/test/{id}', name: 'test_id', methods: ["GET"], requirements: ['id' => '\d+'])]
    public function test_id($id): Response
    {
        //dd($id);
        return $this->render('test/test.html.twig', [
            'ma_case' => 'Test ID',
            'id' => $id,
        ]);
    }

    #[Route('/test/oldposts', name: 'test_oldposts')]
    public function test_oldposts(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findOldPosts(3);
        dd($posts);


        return $this->render('test/test.html.twig', [
            'ma_case' => 'Test ID',
        ]);
    }

    #[Route('/test/nbusers', name: 'test_nbusers')]
    public function test_nbusers(UserRepository $userRepository): Response
    {
        $nbusers = $userRepository->nbUsers();
        dd($nbusers);


        return $this->render('test/test.html.twig', [
            'ma_case' => 'Test ID',
        ]);
    }

    #[Route('/test/nball', name: 'test_nball')]
    public function test_nbAll(PostRepository $postRepository): Response
    {
        $nball = $postRepository->nbAllSubjects();
        dd($nball);


        return $this->render('test/test.html.twig', [
            'ma_case' => 'Test ID',
        ]);
    }

}
