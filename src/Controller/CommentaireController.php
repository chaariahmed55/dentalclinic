<?php

namespace App\Controller;
use App\Entity\Commentaire;
use App\Entity\Article;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
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
     * @Route("/commentaire/getall" , name="getallcommentaires")
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
     * @Route("/commentaire/delete/{id}", name="delete_commentaire", methods={"POST"})
     *
     */
    public function delete($id){
        $commentaire = $this->getDoctrine()->getManager()->getRepository(Commentaire::class)->findOneBy(['id'=>$id]);
        $this->entityManager ->remove($commentaire);
        $this->entityManager ->flush();
        return $this->json('success remove',200);
    }

/**
     * @Route("/commentaire/add", name="add-commentaire", methods={"POST"})
     */
    public function add(Request $request)
    {
        $commentaire = new Commentaire();
        $comment= $request->get('comment');
        $likecomment= $request->get('likecomment');
        $dislikecomment= $request->get('dislikecomment');
        $article= $request->get('article');
        $libelle = $this->getDoctrine()->getManager()->getRepository(Article::class)->findOneBy(['id'=>$article]);
        if(!$libelle){
            return $this->json('error',400);
        }
        $commentaire->setComment($comment);
        $commentaire->setLikecomment($likecomment);
        $commentaire->setDislikecomment($dislikecomment);
        $commentaire->setArticle($libelle);
        $this->entityManager->persist($commentaire);
        $this->entityManager->flush();
        $message = [
            'satus' => 201,
            'message' => 'success'
        ];
        return $this->json($message, 201);
    }

    /**
     * @Route("/commentaire/edit/{id}", name="update-commentaire", methods={"Post"})
     */
    public function update(Request $request, $id)
    {
        $commentaire = $this->getDoctrine()->getManager()->getRepository(Commentaire::class)->findOneBy(['id'=>$id]);
        $comment= $request->get('comment');
        $likecomment= $request->get('likecomment');
        $dislikecomment= $request->get('dislikecomment');
        $article= $request->get('article');
        $libelle = $this->getDoctrine()->getManager()->getRepository(Article::class)->findOneBy(['id'=>$article]);
        if(!$libelle){
            return $this->json('error',400);
        }
        $commentaire->setComment($comment);
        $commentaire->setLikecomment($likecomment);
        $commentaire->setDislikecomment($dislikecomment);
        $commentaire->setArticle($libelle);
        $this->entityManager->persist($commentaire);
        $this->entityManager->flush();
        $message = [
            'satus' => 201,
            'message' => 'success'
        ];
        return $this->json($message, 201);
    }





}
