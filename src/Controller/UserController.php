<?php

namespace App\Controller;
use App\DataFixtures\AppFixtures;
use App\Entity\User;
use App\Entity\Role;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



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
     * @Route("/user/getall/{page}" , name="getallusers")
     */
    public function getall($page)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $users=$this->entityManager
        ->createQuery('SELECT u FROM App\Entity\User u WHERE u.role >= 11')
        ->setMaxResults(6)
        ->setFirstResult($page*6)
        ->getResult();
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($users, null, ['groups' =>
            'user']);
        return $this->json($data, 200);
    }

/**
     * @Route("/user/maxpage" , name="getmaxpage")
     */
    public function getmaxpage()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $users=$this->entityManager
        ->createQuery('SELECT count(u) FROM App\Entity\User u WHERE u.role >= 11')
        ->getResult();
        return $this->json($users, 200);
    }



    /**
     * @Route("/user/getone/{id}" , name="getoneuser")
     */
    public function getone($id)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id' => $id]);
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($users, null, ['groups' =>
            'user']);
        return $this->json($data, 200);
    }


    /**
     * @Route("/user/getonedocteur" , name="getonedocteur")
     */
    public function getonedocteur()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $users=$this->entityManager
        ->createQuery('SELECT u FROM App\Entity\User u WHERE u.role = 9')
        ->getResult();
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($users, null, ['groups' =>
            'user']);
        return $this->json($data, 200);
    }

/**
     * @Route("/user/getonesecretaire" , name="getallonesecretaire")
     */
    public function getonesecretaire()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $users=$this->entityManager
        ->createQuery('SELECT u FROM App\Entity\User u WHERE u.role = 10')
        ->getResult();
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
    public function add(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $nom= $request->get('nom');
        $prenom= $request->get('prenom');
//        $mdp= $request->get('mdp');

        $mdp = $encoder->encodePassword($user, $request->get("password"));
        $adresse= $request->get('adresse');
        $birthdate= $request->get('birthdate');
        $telephone= $request->get('telephone');
        $email= $request->get('email');
        $role= $request->get('role');
        $libelle = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['id'=>$role]);
        if(!$libelle){
            return $this->json('error',400);
        }
        $user->setUsername($email);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setPassword($mdp);
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
    public function update(Request $request, $id,UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id'=>$id]);
        $username= $request->get('username');
        $nom= $request->get('nom');
        $prenom= $request->get('prenom');
//        $mdp= $request->get('mdp');
        $mdp = $encoder->encodePassword($user, $request->get("password"));
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
        $user->setPassword($mdp);
        $user->setAdresse($adresse);
        $user->setBirthdate($birthdate);
        $user->setTelephone($telephone);
        $user->setEmail($email);
        $user->setRole($libelle);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this->json('success',200);
    }


    /**
     * @Route("/user/getbynom/{nom}" , methods={"GET"} ,name="getbyname")
     */
    public function getbyname($nom,Request $request)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findBy(
            ['nom' => $nom]
        );
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($users, null, ['groups' =>
            'user']);
        return $this->json($data, 200);
    }


    /**
     * @Route("/fixture" , methods={"POST"} ,name="load_fixture")
     */
    public function Loadfixture(AppFixtures $load)
    {
        $role = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['id'=>11]);
        $load->load($this->entityManager);
        return $this->json('success',201);
    }


//     /**
//      * @Route("/api/login_check" , methods={"POST"} ,name="login")
//      */
// public function login():JsonResponse
// {
//     $user=$this->getall(0);
//     return $this->json(
//         array(
//             'username'=>$user->getUsername(),
//             'mdp'=>$user->getMdp(),
//         )
//         );
// }



}
