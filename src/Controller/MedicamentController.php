<?php

namespace App\Controller;
use App\Entity\Medicament;
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

class MedicamentController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/medicament", name="medicament")
     */
    public function index(): Response
    {
        return $this->render('medicament/index.html.twig', [
            'controller_name' => 'MedicamentController',
        ]);
    }

/**
     * @Route("/getallmedicaments" , name="getallmedicaments")
     */
    public function getallmedicaments()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $medicaments = $this->getDoctrine()->getManager()->getRepository(Medicament::class)->findAll();
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($medicaments, null, ['groups' =>
            'medicament']);
        return $this->json($data, 200);
    }



    /**
     * @Route("/medicament/delete/{id}", name="delete_medicament", methods={"POST"})
     *
     */
    public function delete($id){
        $medicament = $this->getDoctrine()->getManager()->getRepository(Medicament::class)->findOneBy(['id'=>$id]);
        $this->entityManager ->remove($medicament);
        $this->entityManager ->flush();
        return $this->json('success remove',200);
    }


/**
     * @Route("/medicament/add", name="add-medicament", methods={"POST"})
     */
    public function add(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $medicament = new Medicament();
        $quantiteparjour= $request->get('quantiteparjour');
        $quantitepardose= $request->get('quantitepardose');
        $dure= $request->get('dure');
        $nom= $request->get('nom');
        $fiche= $request->get('fiche');
        $libelle = $this->getDoctrine()->getManager()->getRepository(Fiche::class)->findOneBy(['id'=>$fiche]);
        if(!$libelle){
            return $this->json('error',400);
        }
        $medicament->setQuantiteparjour($quantiteparjour);
        $medicament->setQuantitepardose($quantitepardose);
        $medicament->setDure($dure);
        $medicament->setNom($nom);
        $medicament->setFiche($libelle);
        $em->persist($medicament);
        $em->flush();
        $message = [
            'satus' => 201,
            'message' => 'success'
        ];
        return $this->json($message, 201);
    }



    /**
     * @Route("/medicament/edit/{id}", name="update-medicament", methods={"Post"})
     */
    public function update(Request $request, $id)
    {
        $medicament = $this->getDoctrine()->getManager()->getRepository(Medicament::class)->findOneBy(['id'=>$id]);

        $quantiteparjour= $request->get('quantiteparjour');
        $quantitepardose= $request->get('quantitepardose');
        $dure= $request->get('dure');
        $nom= $request->get('nom');
        $fiche= $request->get('fiche');
        $libelle = $this->getDoctrine()->getManager()->getRepository(Fiche::class)->findOneBy(['id'=>$fiche]);
        if(!$libelle){
            return $this->json('error',400);
        }
        // dump($quantiteparjour);die;
        $medicament->setQuantiteparjour($quantiteparjour);
        $medicament->setQuantitepardose($quantitepardose);
        $medicament->setDure($dure);
        $medicament->setNom($nom);
        $medicament->setFiche($libelle);
        
        $this->entityManager->persist($medicament);
        $this->entityManager->flush();
        return $this->json('success',200);
    }





}
