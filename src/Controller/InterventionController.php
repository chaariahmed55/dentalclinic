<?php

namespace App\Controller;
use App\Entity\Intervention;
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


class InterventionController extends AbstractController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }



    /**
     * @Route("/intervention", name="intervention")
     */
    public function index(): Response
    {
        return $this->render('intervention/index.html.twig', [
            'controller_name' => 'InterventionController',
        ]);
    }



    /**
     * @Route("/intervention/getall" , name="getallintervention")
     */
    public function getall()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $interventions = $this->getDoctrine()->getManager()->getRepository(Intervention::class)->findAll();
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($interventions, null, ['groups' =>
            'intervention']);
        return $this->json($data, 200);
    }



    /**
     * @Route("/intervention/delete/{id}", name="delete_intervention", methods={"POST"})
     *
     */
    public function delete($id){
        $intervention = $this->getDoctrine()->getManager()->getRepository(Intervention::class)->findOneBy(['id'=>$id]);
        $this->entityManager ->remove($intervention);
        $this->entityManager ->flush();
        return $this->json('success remove',200);
    }



    /**
     * @Route("/intervention/add", name="add-intervention", methods={"POST"})
     */
    public function add(Request $request)
    {
        $intervention= new Intervention();
        $type= $request->get('type');
        $prix= $request->get('prix');
        $fiche= $request->get('fiche');
        $libelle = $this->getDoctrine()->getManager()->getRepository(Fiche::class)->findOneBy(['id'=>$fiche]);
        if(!$libelle){
            return $this->json('error',400);
        }
        $intervention->setType($type);
        $intervention->setPrix($prix);
        $intervention->setFiche($libelle);
        $this->entityManager->persist($intervention);
        $this->entityManager->flush();
        $message = [
            'satus' => 201,
            'message' => 'success'
        ];
        return $this->json($message, 200);
    }



    /**
     * @Route("/intervention/edit/{id}", name="update-intervention", methods={"Post"})
     */
    public function update(Request $request, $id)
    {
        $intervention = $this->getDoctrine()->getManager()->getRepository(Intervention::class)->findOneBy(['id'=>$id]);
        $type= $request->get('type');
        $prix= $request->get('prix');
        $fiche= $request->get('fiche');
        $libelle = $this->getDoctrine()->getManager()->getRepository(Fiche::class)->findOneBy(['id'=>$fiche]);
        if(!$libelle){
            return $this->json('error',400);
        }
        $intervention->setType($type);
        $intervention->setPrix($prix);
        $intervention->setFiche($libelle);
        $this->entityManager->persist($intervention);
        $this->entityManager->flush();
        return $this->json('success',200);
    }










}
