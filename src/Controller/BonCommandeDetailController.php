<?php

namespace App\Controller;

use App\CustomEvent\EquipementEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Equipement;
use App\Entity\BonCommande;
use App\Entity\BonCommandeDetail;
use App\Utils\Utils;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class BonCommandeDetailController extends AbstractController
{
    private $em;
    private $serializer;

    public function __construct(
        EntityManagerInterface $em
        )
    {
        $this->em = $em;
        $this->serializer = new Serializer(array(new DateTimeNormalizer(), new ObjectNormalizer()), array(new JsonEncoder()));
    }

    /**
     * @Route("/boncommandedetail/getall", name="all_boncommandedetail", methods={"GET"})
     */
    public function fetch()
    {
        try
        {
            $bon = $this->em->getRepository(BonCommandeDetail::class)->findAll();

            $jsonbon = $this->serializer->serialize($bon, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','Fetch', $jsonbon), true)
        );
    }

    /**
     * @Route("/boncommandedetail/fetchby/{nb}", name="fetch_boncommandedetail", methods={"GET"})
     */
    public function fetchby($nb)
    {
        try
        {
            $qb=$this->em->createQueryBuilder()
                        ->select(['b'])
                        ->from('App\Entity\BonCommandeDetail','b')
                        ->where('b.nboncommande = :nb')
                        ->setParameter('nb',$nb)
                        ->orderBy('b.ordre')
                        ->getQuery();

            //$bon = $this->em->getRepository(BonCommandeDetail::class)->findBy($nb);
            $bon = $qb->getResult();

            $jsonbon = $this->serializer->serialize($bon, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','Fetch', $jsonbon), true)
        );
    }

    /**
     * @Route("/boncommandedetail/deleteby/{nb}/{ce}/{or}", name="delete_boncommandedetail", methods={"GET"})
     */
    public function deleteby($nb, $ce, $or)
    {
        try
        {
            $this->em->getConnection()->beginTransaction();

            $qrd= $this->em->createQueryBuilder()
                    ->delete()
                    ->from('App\Entity\BonCommandeDetail','b')
                    ->where('b.nboncommande = :nb')
                    ->andWhere('b.cequipement = :ce')
                    ->andWhere('b.ordre = :or')
                    ->setParameter('nb', $nb)
                    ->setParameter('ce', $ce)
                    ->setParameter('or', $or)
                    ->getQuery();
            
            $qrd->execute();
            
            $this->em->getConnection()->commit();

        }catch(\Exception $e)
        {
            $this->em->getConnection()->rollBack();
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','DONE', "false"), true)
        );
    }

    /**
     * @Route("/boncommandedetail/edit", name="edit_boncommandedetail", methods={"POST"})
     */
    public function edit(
        Request $request
        )
    {
        try
        {
            //retrieve post Body
            $data = $request->getContent();
            
            //new instance and deserialize json data
            $bon = new BonCommandeDetail();
            $bon = $this->serializer->deserialize($data, BonCommandeDetail::class, 'json');

            //db call
            $this->em->persist($bon);
            $this->em->flush();

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','DONE', "false"), true)
        );
    }
}
