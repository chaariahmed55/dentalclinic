<?php

namespace App\Controller;
use App\Entity\Fiche;
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



class FicheController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/fiche", name="fiche")
     */
    public function index(): Response
    {
        return $this->render('fiche/index.html.twig', [
            'controller_name' => 'FicheController',
        ]);
    }


/**
     * @Route("/fiche/getall" , name="getallfiches")
     */
    public function getall()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $fiches = $this->getDoctrine()->getManager()->getRepository(Fiche::class)->findAll();
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($fiches, null, ['groups' =>
            'fiche']);
        return $this->json($data, 200);
    }



    /**
     * @Route("/fiche/delete/{id}", name="delete_fiche", methods={"POST"})
     *
     */
    public function delete($id){
        $fiche = $this->getDoctrine()->getManager()->getRepository(Fiche::class)->findOneBy(['id'=>$id]);
        $this->entityManager ->remove($fiche);
        $this->entityManager ->flush();
        return $this->json('success remove',200);
    }


    /**
     * @Route("/fiche/add", name="add-fiche", methods={"POST"})
     */
    public function add(Request $request)
    {
        $fiche = new Fiche();
        $date = $request->get('date');
        $description = $request->get('description');
        $fiche->setDate($date);
        $fiche->setDescription($description);
        $this->entityManager->persist($fiche);
        $this->entityManager->flush();
        $message = [
            'satus' => 201,
            'message' => 'success'
        ];
        return $this->json($message, 201);
    }


    
/**
     * @Route("/fiche/edit/{id}", name="update-fiche", methods={"Post"})
     */
    public function update(Request $request, $id)
    {
        $fiche = $this->getDoctrine()->getManager()->getRepository(Fiche::class)->findOneBy(['id'=>$id]);
        $date = $request->get('date');
        $description = $request->get('description');
        $fiche->setDate($date);
        $fiche->setDescription($description);
        $this->entityManager->persist($fiche);
        $this->entityManager->flush();
        return $this->json('success',200);
    }












}
