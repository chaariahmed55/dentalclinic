<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Role;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer
;
class UserController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/user/getall" , name="getallusers")
     */
    public function getall()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($users, null, ['groups' =>
            'user']);
        return $this->json($data, 200);
    }


    /**
     * @Route("/user/delete/{id}", name="delete_user", methods={"POST"})
     *
     */
    public function delete($id){
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id'=>$id]);
        $this->entityManager ->remove($user);
        $this->entityManager ->flush();
        return $this->json('success remove',200);
    }

/**
     * @Route("/user/add", name="add-user", methods={"POST"})
     */
    public function add(Request $request)
    {
        $user = new User();
        $username= $request->get('username');
        $nom= $request->get('nom');
        $prenom= $request->get('prenom');
        $mdp= $request->get('mdp');
        $adresse= $request->get('adresse');
        $birthdate= $request->get('birthdate');
        $telephone= $request->get('telephone');
        $email= $request->get('email');
        $role= $request->get('role');
        $libelle = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['id'=>$role]);
        if(!$libelle){
            return $this->json('error',400);
        }
        $user->setUsername($username);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setMdp($mdp);
        $user->setAdresse($adresse);
        $user->setBirthdate($birthdate);
        $user->setTelephone($telephone);
        $user->setEmail($email);
        $user->setRole($libelle);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $message = [
            'satus' => 201,
            'message' => 'success'
        ];
        return $this->json($message, 201);
    }

    /**
     * @Route("/user/edit/{id}", name="update-user", methods={"Post"})
     */
    public function update(Request $request, $id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id'=>$id]);
        $username= $request->get('username');
        $nom= $request->get('nom');
        $prenom= $request->get('prenom');
        $mdp= $request->get('mdp');
        $adresse= $request->get('adresse');
        $birthdate= $request->get('birthdate');
        $telephone= $request->get('telephone');
        $email= $request->get('email');
        $role= $request->get('role');
        $libelle = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['id'=>$role]);
        if(!$libelle){
            return $this->json('error',400);
        }
        $user->setUsername($username);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setMdp($mdp);
        $user->setAdresse($adresse);
        $user->setBirthdate($birthdate);
        $user->setTelephone($telephone);
        $user->setEmail($email);
        $user->setRole($libelle);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this->json('success',200);
    }







}
