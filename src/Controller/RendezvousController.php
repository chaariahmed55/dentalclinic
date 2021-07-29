<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Rendezvous;
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

class RendezvousController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/rendezvous", name="rendezvous")
     */
    public function index(): Response
    {
        return $this->render('rendezvous/index.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);
    }

    
    /**
     * @Route("/rendezvous/getall" , name="getallrendezvous")
     */
    public function getall()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $rendezvous = $this->getDoctrine()->getManager()->getRepository(Rendezvous::class)->findAll();
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($rendezvous, null, ['groups' =>
            'rendezvous']);
        return $this->json($data, 200);
    }


    /**
     * @Route("/rendezvous/delete/{id}", name="delete_rendezvous", methods={"POST"})
     *
     */
    public function delete($id){
        $rendezvous = $this->getDoctrine()->getManager()->getRepository(Rendezvous::class)->findOneBy(['id'=>$id]);
        $this->entityManager ->remove($rendezvous);
        $this->entityManager ->flush();
        return $this->json('success remove',200);
    }

/**
     * @Route("/rendezvous/add", name="add-rendezvous", methods={"POST"})
     */
    public function add(Request $request)
    {
        $rendezvous = new Rendezvous();
        $date= $request->get('date');
        $dateadmission= $request->get('dateadmission');
        $datenext= $request->get('datenext');
        $user= $request->get('user');
        $libelle = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id'=>$user]);
        if(!$libelle){
            return $this->json('error',400);
        }
        $rendezvous->setDate($date);
        $rendezvous->setDateadmission($dateadmission);
        $rendezvous->setDatenext($datenext);
        $rendezvous->setUser($libelle);
        $this->entityManager->persist($rendezvous);
        $this->entityManager->flush();
        $message = [
            'satus' => 201,
            'message' => 'success'
        ];
        return $this->json($message, 201);
    }

    /**
     * @Route("/rendezvous/edit/{id}", name="update-rendezvous", methods={"Post"})
     */
    public function update(Request $request, $id)
    {
        $rendezvous = $this->getDoctrine()->getManager()->getRepository(Rendezvous::class)->findOneBy(['id'=>$id]);
        $date= $request->get('date');
        $dateadmission= $request->get('dateadmission');
        $datenext= $request->get('datenext');
        $user= $request->get('user');
        $libelle = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id'=>$user]);
        if(!$libelle){
            return $this->json('error',400);
        }
        $rendezvous->setDate($date);
        $rendezvous->setDateadmission($dateadmission);
        $rendezvous->setDatenext($datenext);
        $rendezvous->setUser($libelle);
        $this->entityManager->persist($rendezvous);
        $this->entityManager->flush();
        $message = [
            'satus' => 201,
            'message' => 'success'
        ];
        return $this->json($message, 201);
    }



}
