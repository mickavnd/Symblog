<?php

namespace App\DataFixtures;

use App\Entity\Post\Category;
use App\Entity\Post\Tags;
use App\Repository\PostRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{     
        public function __construct(private PostRepository $postRepository)
        {
           
        }

    public function load(ObjectManager $manager): void
    {   
        $faker = Factory::create('fr_FR');
        $posts = $this->postRepository->findAll();

        //category
        $categoires= [];
        for ($i=0; $i < 10 ; $i++) { 
           
            $category = new Category();
            $category->setName($faker->words(1,true) .' '.$i)
                     ->setDescription(mt_rand(0 ,1)=== 1 ? $faker->realText(254):null);

            $manager->persist($category);  
            $categoires [] = $category ;       

        }

        foreach($posts as $post)
        {
            for ($i=0; $i < mt_rand(1, 5) ; $i++) { 
                
                $post->addCategory($categoires[mt_rand(0,count($categoires) - 1)]);
            }
            
            
        }

        //Tags
        $tags= [];
        for ($i=0; $i < 10 ; $i++) { 
           
            $tag = new Tags();
            $tag->setName($faker->words(1,true) .' '.$i)
                     ->setDescription(mt_rand(0 ,1)=== 1 ? $faker->realText(254):null);

            $manager->persist($tag);  
            $tags [] = $tag ;       

        }

        foreach($posts as $post)
        {
            for ($i=0; $i < mt_rand(1,5) ; $i++) { 
                $post->addTags($tags[mt_rand(0,count($tags) - 1)]);
            }
            
            
        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [PostFixtures::class];
    }
}
