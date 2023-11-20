<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {
        //$posts = $postRepository->findAll();
        $posts = $postRepository->findLastPosts();
        $oldposts = $postRepository->findOldPosts(4);
        //dd($oldposts);

        $categories = $categoryRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'oldposts' => $oldposts,
            'categories' => $categories
        ]);
    }

    #[Route('/post/add', name: 'post_add')]
    //#[IsGranted('ROLE_USER')]
    public function addPost(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $post->setActive(false);
            $em = $doctrine->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Votre article a été proposé !');
            return $this->redirectToRoute('home');
        }
        
        return $this->render('post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //#[Route('/post/{id}', name: 'post_view', methods: ["GET"], requirements: ['id' => '\d+'])]
    #[Route('/post/{slug}', name: 'post_view')]
    public function post_view(Post $post, Request $request, ManagerRegistry $doctrine): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setPost($post);
            $em = $doctrine->getManager();
            $em->persist($comment);
            $em->flush();
            //return $this->redirectToRoute('home');
            $this->addFlash('success', 'Votre commentaire a bien été enregistré !');
            return $this->redirectToRoute('post_view', array('slug' => $post->getSlug()));
        }

        return $this->render('post/post.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    #[Route('/category/{slug}', name: 'posts_by_category')]
    public function posts_by_category(Category $category, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('post/category.html.twig', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

}
