<?php

namespace App\Controller;
use App\Entity\Commentaire;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CommentaireController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }



    /**
     * @Route("/commentaire", name="commentaire")
     */
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }


/**
     * @Route("/commentaire/getall" , name="getallcommentaires", methods={"GET"})
     */
    public function getall()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $commentaire = $this->getDoctrine()->getManager()->getRepository(Commentaire::class)->findAll();
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($commentaire, null, ['groups' =>
            'commentaire']);
        return $this->json($data, 200);
    }


    /**
     * @Route("/commentaire/delete/{id}", name="delete_commentaire", methods={"DELETE"})
     *
     */
    public function delete($id){
        $commentaire = $this->getDoctrine()->getManager()->getRepository(Commentaire::class)->findOneBy(['id'=>$id]);
        $this->entityManager -> remove($commentaire);
        $this->entityManager -> flush();
        return $this->json('success remove',200);
    }

/**
     * @Route("/commentaire/add", name="add-commentaire", methods={"POST"})
     */
    public function add(Request $request, NormalizerInterface $normalizer)
    {
        $commentaire = new Commentaire();
        $commentaire -> setComment($request -> get('comment'));
        $commentaire -> setDate($request -> get('date'));
        $article_id= $request->get('article');
        $article = $this->getDoctrine()->getManager()->getRepository(Article::class)->find($article_id);
        if(!$article){
            return $this->json('error',400);
        }
        $commentaire -> setArticle($article);
        $this->entityManager->persist($commentaire);
        $this->entityManager->flush();
        //deserialize the data
        $response = $normalizer->normalize($article, null, ['groups' =>
            'commentaire']);
        return $this->json($response, 201);

    }

    /**
     * @Route("/commentaire/edit/{id}", name="update-commentaire", methods={"POST"})
     */
    public function update(Request $request, $id , NormalizerInterface $normalizer){

        $commentaire_to_update =$this->entityManager->getRepository(Commentaire::class)->find($id);
        $article_id = $request ->get('article');
        $article = $this->entityManager -> getRepository(Article::class)->find($article_id);
        if(!$article){
            return $this->json('error',400);
        }
        $commentaire_to_update -> setComment($request -> get('comment'));
        $commentaire_to_update -> setDate($request -> get('date'));
        $commentaire_to_update -> setArticle($article);
        $this->entityManager->persist($commentaire_to_update);
        $this->entityManager->flush();
        //deserialize the data
        $response = $normalizer->normalize($commentaire_to_update, null, ['groups' =>
            'commentaire']);
        return $this->json($response, 200);
    }
    /**
     * @Route("/commentaire/getComments/{id}", name="getComments", methods={"GET"})
     */
    public function listCommentByArticle($id, ArticleRepository $articleRepository, CommentaireRepository $commentaireRepository){

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $article = $articleRepository->find($id);
        $comments = $commentaireRepository->listCommentByArticle($article->getId());
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($comments, null, ['groups' => 'commentaire']);
        return $this->json($data, 200);
    }





}
