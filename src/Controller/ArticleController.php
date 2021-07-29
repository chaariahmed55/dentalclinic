<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;



class ArticleController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/article", name="article")
     */
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }


    /**
     * @Route("/article/getall" , name="getallarticles", methods={"GET"})
     */
    public function getall()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $articles = $this->getDoctrine()->getManager()->getRepository(Article::class)->findAll();
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($articles, null, ['groups' =>
            'article']);
        return $this->json($data, 200);
    }

    /**
     * @Route("/article/getArticle/{id}" , name="getarticle", methods={"GET"})
     */
    public function getarticle($id)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $articles = $this->getDoctrine()->getManager()->getRepository(Article::class)->findOneBy(['id'=>$id]);
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($articles, null, ['groups' =>
            'article']);
        return $this->json($data, 200);
    }


    /**
     * @Route("article/add", name="add-article", methods={"POST"})
     */
    public function addArticle (Request $request , NormalizerInterface $normalizer)
    {

               $article = new Article();
               $article -> setTitle($request -> get('title'));
               $article -> setDescription($request -> get('description'));
               $article -> setImagepath($request -> get('imagepath'));
               $article -> setDate($request -> get('date'));
               $user_id = $request ->get('user');
               $user = $this->entityManager -> getRepository(User::class)->find($user_id);
                if(!$user){
                   return $this->json('error',400);
               }
               $article -> setUser($user);
               $this->entityManager->persist($article);
               $this->entityManager->flush();
               //deserialize the data
               $response = $normalizer->normalize($article, null, ['groups' =>
                   'article' ]);
               return $this->json($response, 200);
    }



           /**
            * @Route("/article/delete/{id}", name="delete_article", methods={"DELETE"})
            *
            */
    public function delete($id){
        $article = $this->getDoctrine()->getManager()->getRepository(Article::class)->findOneBy(['id'=>$id]);
        $this->entityManager ->remove($article);
        $this->entityManager ->flush();
        return $this->json('success remove',200);
    }


    /**
     * @Route("/article/edit/{id}", name="update-article", methods={"POST"})
     */
    public function update(Request $request, $id , NormalizerInterface $normalizer)
    {


        $article_to_update =$this->entityManager->getRepository(Article::class)->find($id);
        $user_id = $request ->get('user');
        $user = $this->entityManager -> getRepository(User::class)->find($user_id);
        if(!$user){
            return $this->json('error',400);
        }
        $article_to_update -> setTitle($request -> get('title'));
        $article_to_update -> setDescription($request -> get('description'));
        $article_to_update -> setImagepath($request -> get('imagepath'));
        $article_to_update -> setDate($request -> get('date'));
        $article_to_update -> setUser($user);
        //$this->entityManager->persist($article_to_update);
        $this->entityManager->flush();
        //deserialize the data
        $response = $normalizer->normalize($article_to_update, null, ['groups' =>
            'article']);
        return $this->json($response, 200);
    }

    /**
     * @Route("/article/searchBytitle", name="search_title", methods={"GET"})
     */
    public function searchbytitle(ArticleRepository $articleRepository, Request $request){

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $title = $request -> get('search');
        $articles = $articleRepository -> searchTitle($title);
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($articles, null, ['groups' => 'article']);
        return $this->json($data, 200);
    }

}
