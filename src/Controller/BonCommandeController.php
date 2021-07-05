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
use App\Utils\BCBody;
use App\Utils\Utils;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class BonCommandeController extends AbstractController
{

    private $em;
    private $serializer;

    public function __construct(
        EntityManagerInterface $em
        )
    {
        $this->em = $em;
        $this->serializer = new Serializer(array(new DateTimeNormalizer('d-m-Y'), new ObjectNormalizer()), array(new JsonEncoder()));
    }

    /**
     * @Route("/boncommande/getall", name="all_boncommande", methods={"GET"})
     */
    public function fetch()
    {
        try
        {
            $bon = $this->em->getRepository(BonCommande::class)->findAll();

            $jsonbon = $this->serializer->serialize($bon, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "[]"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','Fetch', $jsonbon), true)
        );
    }

    /**
     * @Route("/boncommande/getby/{nb}", name="get_boncommande", methods={"GET"})
     */
    public function fetchby($nb)
    {
        try
        {
            $bon = $this->em->getRepository(BonCommande::class)->find($nb);

            $jsonbon = $this->serializer->serialize($bon, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "[]"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','Fetch', $jsonbon), true)
        );
    }

    /**
     * @Route("/boncommande/deleteby/{nb}", name="delete_boncommande", methods={"GET"})
     */
    public function deleteby($nb)
    {
        try
        {
            $this->em->getConnection()->beginTransaction();

            $qrd= $this->em->createQueryBuilder()
                    ->delete()
                    ->from('App\Entity\BonCommandeDetail','b')
                    ->where('b.nboncommande = :nb')
                    ->setParameter('nb', $nb)
                    ->getQuery();

            $qr= $this->em->createQueryBuilder()
                    ->delete()
                    ->from('App\Entity\BonCommande','b')
                    ->where('b.nboncommande = :nb')
                    ->setParameter('nb', $nb)
                    ->getQuery();
            
            $qrd->execute();
            $qr->execute();

            $this->em->getConnection()->commit();

        }catch(\Exception $e)
        {
            $this->em->getConnection()->rollBack();
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "[]"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','DONE', "[]"), true)
        );
    }

    /**
     * @Route("/boncommande/edit", name="edit_boncommande", methods={"POST"})
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
            $bon = new BonCommande();
            $bon = $this->serializer->deserialize($data, BonCommande::class, 'json');

            //db call
            $this->em->persist($bon);
            $this->em->flush();

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "[]"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','DONE', "[]"), true)
        );
    }

    /**
     * @Route("/boncommande/new", name="new_boncommande", methods={"POST"})
     */
    public function add(
        Request $request
        )
    {
        try
        {
            //retrieve post Body
            $data = $request->getContent();

            //new instance and deserialize json data
            $bon = new BCBody();
            $bon = $this->serializer->deserialize($data, BCBody::class, 'json');
            $entete = $bon->getentete();
            $details = $bon->getdetail();

            $bonc = new BonCommande();
            $bonc = $this->serializer->deserialize($this->serializer->serialize($entete, 'json'), BonCommande::class, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['nboncommande','dateboncommande']]);
            $bonc->setdateboncommande( (new \DateTime('now')));
            $this->em->getConnection()->beginTransaction();

            //db call
            $this->em->persist($bonc);
            $this->em->flush();

            foreach ($details as $index => $detail) {
                $bdetai = new BonCommandeDetail();
                $bdetai = $this->serializer->deserialize($this->serializer->serialize($detail, 'json'), BonCommandeDetail::class, 'json');
                $bdetai->setnboncommande($bonc->getnboncommande());
                $bdetai->setordre($index+1);
                $this->em->persist($bdetai);
            }

            $this->em->flush();            

            $this->em->getConnection()->commit();

        }catch(\Exception $e)
        {
            $this->em->getConnection()->rollBack();

            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "[]"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','DONE', "[]"), true)
        );
    }

}
