<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class AppFixtures extends Fixture
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }



    public function load(ObjectManager $manager)
    {
        $role = new Role();
        $role->setRole('Patient');
        $manager->persist($role);
        $manager->flush();
        //  $role = $this->getContainer()->get('doctrine')->getManager()->getRepository(Role::class)->findOneBy(['id'=>11]);
         //$role = $this->getDoctrine()->getManager()->getRepository(Role::class)->findOneBy(['id'=>$role]);
        // $role = new Role("Patient");
        // $this->entityManager->persist($role);
        // $manager->flush();
        for ($i=0; $i <20; $i++) { 
            $user=new User();
            $user->setUsername("ryukfixture");
            $user->setNom("chaarifixture");
            $user->setPrenom("ahmedfixture");
            $user->setMdp("mdpfixture");
            $user->setAdresse("adressefixture");
            $user->setBirthdate("birthdatefixture");
            $user->setTelephone(123456789);
            $user->setEmail("emailfixture");
            $user->setRole($role);
            $manager->persist($user);
        }
        $manager->flush();
        
    }
}
