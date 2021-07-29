<?php

namespace App\Controller;
use App\Entity\Fiche;
use App\Entity\User;
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
     * @Route("/fiche/getallbyuser/{id}" , name="getallfichesbyuser")
     */
    public function getallbyiduser($id)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $fiches = $this->getDoctrine()->getManager()->getRepository(Fiche::class)->findBy(['user' => $id]);
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
        $user= $request->get('user');
        $libelle = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id'=>$user]);
        $fiche->setDate($date);
        $fiche->setDescription($description);
        $fiche->setUser($libelle);
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
        
        $description = $request->get('description');
        $fiche->setDescription($description);
        
        $this->entityManager->persist($fiche);
        $this->entityManager->flush();
        return $this->json('success',200);
    }


    /**
     * @Route("/fiche/getbydate/{id}&{date}" , methods={"GET"} ,name="getbydate")
     */
    public function getbydate($date,$id,Request $request)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $fiches = $this->getDoctrine()->getManager()->getRepository(Fiche::class)->findBy(['user' => $id,'date'=>$date]);
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($fiches, null, ['groups' =>
            'fiche']);
        return $this->json($data, 200);
    }

    

    /**
     * @Route("/fiche/getallfiche/{id}" , methods={"GET"} ,name="getallfiche")
     */
    public function getallfiche($id)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $fiches =$this->entityManager
        ->createQuery('SELECT count(f) FROM App\Entity\Fiche f WHERE f.user = :id')
        ->setParameter('id', $id)
        ->getResult();

        return $this->json($fiches, 200);
    }

    /**
     * @Route("/fiche/getallbyusers/{id}&{page}" , name="getallfichesbyuser")
     */
    public function getallbyiduserandpagination($id,$page)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $fiches =$this->entityManager
        ->createQuery('SELECT f FROM App\Entity\Fiche f WHERE f.user = :id')
        ->setParameter('id', $id)
        ->setMaxResults(5)
        ->setFirstResult($page*5)
        ->getResult();
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($fiches, null, ['groups' =>
            'fiche']);


        return $this->json($data, 200);
    }








}
