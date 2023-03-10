<?php

namespace App\Controller\Blog;

use App\Entity\Post\Category;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'category_index')]
    public function index( Category $category,PostRepository $postRepository ,Request $request): Response
    {       
        $posts = $postRepository->findPublished($request->query->getInt('page',1),$category);

       

        return $this->render('pages/category/index.html.twig', [
            'category' => $category,
            'posts'=>$posts

        ]);
    }
}
