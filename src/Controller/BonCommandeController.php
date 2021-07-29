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
use App\Utils\BCLineChart;
use App\Utils\BCRange;
use App\Utils\Utils;
use DateTimeZone;
use Exception;
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
        $this->serializer = new Serializer(array(new DateTimeNormalizer('d/m/Y'), new ObjectNormalizer()), array(new JsonEncoder()));
    }

    /**
     * @Route("/boncommande/getall", name="all_boncommande", methods={"GET"})
     */
    public function fetch()
    {
        try
        {
            //$bon = $this->em->getRepository(BonCommande::class)->findAll();

            $qb=$this->em->createQueryBuilder()
                ->select('b')
                ->from('App\Entity\BonCommande','b')
                ->where('b.etat != \'ANNULER\'')
                ->orderBy('b.nboncommande','DESC')
                ->getQuery();

            $bon = $qb->getResult();

            $jsonbon = $this->serializer->serialize($bon, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','Fetchby', $jsonbon), true)
        );
    }

    /**
     * @Route("/boncommande/range/{dd}/{df}", name="range_boncommande", methods={"GET"})
     */
    public function range($dd, $df)
    {
        try
        {
            // $dd = \DateTime::createFromFormat("d-m-Y",  $dd, new DateTimeZone('Africa/Tunis'));
            // $df = \DateTime::createFromFormat("d-m-Y",  $df, new DateTimeZone('Africa/Tunis'));
            //$bon = $this->em->getRepository(BonCommande::class)->findAll();
            $bcrange = new BCRange();

            $qbh=$this->em->createQueryBuilder()
                ->select('b.etat as etat, count(b) as count')
                ->from('App\Entity\BonCommande','b')
                ->where('b.dateboncommande between :dd and :df')
                ->orderBy('b.nboncommande','DESC')
                ->groupBy('b.etat')
                ->setParameter(':dd', $dd)
                ->setParameter(':df', $df)
                ->getQuery();

            $bonh = $qbh->getResult();

            $bcrange->setHead($bonh);

            $qb=$this->em->createQueryBuilder()
                ->select('b')
                ->from('App\Entity\BonCommande','b')
                ->where('b.etat != \'ANNULER\'')
                ->andWhere('b.dateboncommande between :dd and :df')
                ->orderBy('b.nboncommande','DESC')
                ->setParameter(':dd', $dd)
                ->setParameter(':df', $df)
                ->getQuery();

            $bon = $qb->getResult();

            $bcrange->setBody($bon);

            $jsonbon = $this->serializer->serialize($bcrange, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','Fetchby', $jsonbon), true)
        );
    }

    /**
     * @Route("/boncommande/fetchby/{nb}", name="get_boncommande", methods={"GET"})
     */
    public function fetchby($nb)
    {
        try
        {
            //$bon = $this->em->getRepository(BonCommande::class)->find($nb);

            $bcbody = new BCBody();

            //fetch entete
            $qbe=$this->em->createQueryBuilder()
                ->select(['b'])
                ->from('App\Entity\BonCommande','b')
                ->where('b.nboncommande = :nb')
                ->andWhere('b.etat != \'ANNULER\'')
                ->setParameter('nb',$nb)
                ->getQuery();

            $bonen = $qbe->getSingleResult();

            //$jsonentete = $this->serializer->serialize($bonen, 'json');

            $bcbody->setentete($bonen);

            //fetch detail
            $qbd=$this->em->createQueryBuilder()
                        ->select(['b'])
                        ->from('App\Entity\BonCommandeDetail','b')
                        ->where('b.nboncommande = :nb')
                        ->setParameter('nb',$nb)
                        ->orderBy('b.ordre')
                        ->getQuery();

            //$bon = $this->em->getRepository(BonCommandeDetail::class)->findBy($nb);
            $bondet = $qbd->getResult();

            //$jsondetail = $this->serializer->serialize($bondet, 'json');

            foreach ($bondet as $index => $detail) {
                $detail->setprix($detail->getprix());
            }

            $bcbody->setdetail($bondet);

            $jsonbcbody = $this->serializer->serialize($bcbody, 'json');

        }catch(\Doctrine\ORM\NonUniqueResultException $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('OK', $e->getMessage(), "false"), true)
            );
        }
        catch(\Doctrine\ORM\NoResultException $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('OK', $e->getMessage(), "false"), true)
            );
        }
        catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','Fetch', $jsonbcbody), true)
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
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','DONE', "false"), true)
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
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','DONE', "false"), true)
        );
    }

    /**
     * @Route("/boncommande/save", name="save_boncommande", methods={"POST"})
     */
    public function save(
        Request $request
        )
    {
        try
        {
            $this->em->getConnection()->beginTransaction();

            //retrieve post Body
            $data = $request->getContent();

            //new instance and deserialize json data
            $bon = new BCBody();
            $bon = $this->serializer->deserialize($data, BCBody::class, 'json');
            $entete = $bon->getentete();
            $details = $bon->getdetail();

            $bonc = new BonCommande();
            $bonc = $this->serializer->deserialize($this->serializer->serialize($entete, 'json'), BonCommande::class, 'json'); //[AbstractNormalizer::IGNORED_ATTRIBUTES => ['nboncommande']]
            //$bonc->setdateboncommande( (new \DateTime('now')));

            $clonebonc = clone $bonc; 

            $findbon= $this->em->getRepository(BonCommande::class)->find($bonc->getnboncommande());
            if($findbon){
                if($findbon->getetat()!=="EN ATTENTE")
                {
                   throw new Exception('Enregistrement echoué.');
                }
                $bonc = $findbon;
                $bonc->setdateboncommandedate($clonebonc->getdateboncommande()); 
                $bonc->setcfournisseur($clonebonc->getcfournisseur()); 
                $bonc->setraisonsocialefournisseur($clonebonc->getraisonsocialefournisseur()); 
                $bonc->setmontant($clonebonc->getmontant()); 
                $bonc->setetat($clonebonc->getetat()); 
                $bonc->setbvalid($clonebonc->getbvalid());

                //delete details
                $qrd= $this->em->createQueryBuilder()
                    ->delete()
                    ->from('App\Entity\BonCommandeDetail','b')
                    ->where('b.nboncommande = :nb')
                    ->setParameter('nb', $bonc->getnboncommande())
                    ->getQuery();
            
                $qrd->execute();
            }else{
                $bonc->setnboncommande(NULL);
            }

            //db call
            $this->em->persist($bonc);
            $this->em->flush();

            foreach ($details as $index => $detail) {
                $bdetai = new BonCommandeDetail();
                $bdetai = $this->serializer->deserialize($this->serializer->serialize($detail, 'json'), BonCommandeDetail::class, 'json');
                $bdetai->setnboncommande($bonc->getnboncommande());
                //$bdetai->setordre($index+1);
                $this->em->persist($bdetai);
                
                //BC valider augmente le stock
                if($bonc->getbvalid()){
                    $equip = new Equipement();
                    $equip =  $this->em->getRepository(Equipement::class)->find($bdetai->getcequipement());
                    $equip->setquantite($equip->getquantite() + $bdetai->getquantite());
                    $this->em->persist($equip);
                    $this->em->flush();
                }
            }

            $this->em->flush();            

            $this->em->getConnection()->commit();

        }catch(\Exception $e)
        {
            $this->em->getConnection()->rollBack();

            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage()." TRACE ".$e->getTraceAsString() , "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','DONE', "false"), true)
        );
    }

        /**
     * @Route("/boncommande/annuler/{nb}", name="annuler_boncommande", methods={"GET"})
     */
    public function annuler($nb)
    {
        try
        {
            $this->em->getConnection()->beginTransaction();

            $findbon= $this->em->getRepository(BonCommande::class)->find($nb);
            if($findbon){
                
                if($findbon->getetat()==="VALIDER")
                    throw new Exception('INTERDIT');

                $findbon->setetat('ANNULER');
                //db call
                $this->em->persist($findbon);
                $this->em->flush();    
            }      

            $this->em->getConnection()->commit();

        }catch(\Exception $e)
        {
            $this->em->getConnection()->rollBack();

            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage()." TRACE ".$e->getTraceAsString() , "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','DONE', "false"), true)
        );
    }

    /**
     * @Route("/boncommande/linechart", name="linechart_boncommande", methods={"GET"})
     */
    public function linechart()
    {
        try
        {
            $bcline = array();

            $qbh=$this->em->createQueryBuilder()
                ->select('month(b.dateboncommande) as mn, sum(b.montant) as sum')
                ->from('App\Entity\BonCommande','b')
                ->where('month(b.dateboncommande) <= month(now())')
                ->groupBy('mn')
                ->orderBy('month(b.dateboncommande)','DESC')
                ->getQuery();

            $lines = $qbh->getResult();

            foreach($lines as $val){
                $l = new BCLineChart();
                $l->setMn($val['mn']);
                $l->setSum($val['sum']);
                
                array_push($bcline, $l);
            }

            $jsonbon = $this->serializer->serialize($bcline, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','Fetchby', $jsonbon), true)
        );
    }

}
