<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


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
     * @Route("/article/getall" , name="getallarticles")
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
     * @Route("/article/delete/{id}", name="delete_article", methods={"POST"})
     *
     */
    public function delete($id){
        $article = $this->getDoctrine()->getManager()->getRepository(Article::class)->findOneBy(['id'=>$id]);
        $this->entityManager ->remove($article);
        $this->entityManager ->flush();
        return $this->json('success remove',200);
    }

/**
     * @Route("/article/add", name="add-article", methods={"POST"})
     */
    public function add(Request $request)
    {
        $article = new Article();
        $description= $request->get('description');
        $title= $request->get('title');
        $imagepath= $request->get('imagepath');
        $date= $request->get('date');
        $user= $request->get('user');
        $libelle = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id'=>$user]);
        if(!$libelle){
            return $this->json('error',400);
        }
        $article->setDescription($description);
        $article->setTitle($title);
        $article->setImagepath($imagepath);
        $article->setDate($date);
        $article->setUser($libelle);
        $this->entityManager->persist($article);
        $this->entityManager->flush();
        $message = [
            'satus' => 201,
            'message' => 'success'
        ];
        return $this->json($message, 201);
    }

    /**
     * @Route("/article/edit/{id}", name="update-article", methods={"Post"})
     */
    public function update(Request $request, $id)
    {
        $article = $this->getDoctrine()->getManager()->getRepository(Article::class)->findOneBy(['id'=>$id]);
        $description= $request->get('description');
        $title= $request->get('title');
        $imagepath= $request->get('imagepath');
        $date= $request->get('date');
        $user= $request->get('user');
        $libelle = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id'=>$user]);
        if(!$libelle){
            return $this->json('error',400);
        }
        $article->setDescription($description);
        $article->setTitle($title);
        $article->setImagepath($imagepath);
        $article->setDate($date);
        $article->setUser($libelle);
        $this->entityManager->persist($article);
        $this->entityManager->flush();
        return $this->json('success',200);
    }





}
