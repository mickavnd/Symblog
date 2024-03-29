<?php

namespace App\Controller\Blog;

use App\Entity\Post\Post;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{
    #[Route('/', name: 'app_post' ,methods:['GET'])]
    public function index(PostRepository $repository,Request $request): Response
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class,$searchData);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

           $searchData->page =$request->query->getInt('page', 1);
           $posts = $repository->findBySearch($searchData);
          
           return $this->render('pages/post/index.html.twig',[
            'form'=>$form->createView(),
            'post'=> $posts
           ]);
        }

        
        return $this->render('pages/post/index.html.twig', [
            'form' => $form->createView(),
            'post' => $repository->findPublished($request->query->getInt('page',1)),
        ]);
    }

    
    /**
     * Undocumented function
     *
     * @param Post $post
     * @return Response
     */
    #[Route('/article/{slug}', name:'post_show', methods:['GET'])]
    public function show(Post $post):Response
    {
        // dd($post);
        
        return $this->render('pages/post/show.html.twig',[
            'posts'=> $post
        ]);
    }
}

