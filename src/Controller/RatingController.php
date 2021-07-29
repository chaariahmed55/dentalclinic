<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Rating;
use App\Repository\ArticleRepository;
use App\Repository\RatingRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RatingController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/rating", name="rating")
     */
    public function index(): Response
    {
        return $this->render('rating/index.html.twig', [
            'controller_name' => 'RatingController',
        ]);
    }

    /**
     * @Route("/rating/getRating/{id}", name="getRating", methods={"GET"})
     */
    public function listRatingByArticle($id, ArticleRepository $articleRepository, RatingRepository $ratingRepository){

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $article = $articleRepository->find($id);
        $rating = $ratingRepository->listRatingByArticle($article->getId());
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($rating, null, ['groups' => 'rating']);
        return $this->json($data, 200);
    }


}
