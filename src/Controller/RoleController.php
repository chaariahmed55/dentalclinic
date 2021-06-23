<?php

namespace App\Controller;
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
use Symfony\Component\Serializer\Serializer;


class RoleController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/role", name="role")
     */
    public function index(): Response
    {
        return $this->render('role/index.html.twig', [
            'controller_name' => 'RoleController',
        ]);
    }

    /**
     * @Route("/role/getall" , name="getallroles")
     */
    public function getall()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $roles = $this->getDoctrine()->getManager()->getRepository(Role::class)->findAll();
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);
        $data = $serializer->normalize($roles, null, ['groups' =>
            'role']);
        return $this->json($data, 200);
    }

    /**
     * @Route("/role/delete/{id}", name="delete_role", methods={"POST"})
     *
     */
    public function delete($id){
        $role = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['id'=>$id]);
        $this->entityManager ->remove($role);
        $this->entityManager ->flush();
        return $this->json('success remove',200);
    }

    /**
     * @Route("/role/add", name="add-role", methods={"POST"})
     */
    public function add(Request $request)
    {
        $role = new Role();
        $libelle = $request->get('role');
        $role->setRole($libelle);
        $this->entityManager->persist($role);
        $this->entityManager->flush();
        $message = [
            'satus' => 201,
            'message' => 'success'
        ];
        return $this->json($message, 201);
    }

/**
     * @Route("/role/edit/{id}", name="update-role", methods={"Post"})
     */
    public function update(Request $request, $id)
    {
        $role = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['id'=>$id]);
        $libelle = $request->get('role');
        $role->setRole($libelle);
        $this->entityManager->persist($role);
        $this->entityManager->flush();
        return $this->json('success',200);
    }






}
