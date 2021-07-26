<?php

namespace App\Controller;

use App\CustomEvent\EquipementEvent;
use App\Entity\BonCommandeDetail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Equipement;
use App\Utils\Utils;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class EquipementController extends AbstractController
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
     * @Route("/equipement/getall", name="all_equipement", methods={"GET"})
     */
    public function fetch()
    {
        try
        {
            //$equips = $this->em->getRepository(Equipement::class)->findAll();

            $qb=$this->em->createQueryBuilder()
                ->select(['e'])
                ->from('App\Entity\Equipement','e')
                ->orderBy('e.libequipement','ASC')
                ->getQuery();

            $equips = $qb->getResult();

            $jsonequips = $this->serializer->serialize($equips, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','Fetch', $jsonequips), true)
        );
    }

    /**
     * @Route("/equipement/getby/{code}", name="get_equipement", methods={"GET"})
     */
    public function fetchby(string $code)
    {
        try
        {
            //$equips = $this->em->getRepository(Equipement::class)->find($code);

            $qb=$this->em->createQueryBuilder()
                ->select(['e'])
                ->from('App\Entity\Equipement','e')
                ->where('e.cequipement = :code')
                ->setParameter('code', $code)
                ->orderBy('e.libequipement','ASC')
                ->getQuery();

            $equips = $qb->getResult();

            $jsonequips = $this->serializer->serialize($equips, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','FetchBy', $jsonequips), true)
        );
    }

    /**
     * @Route("/equipement/save", name="save_equipement", methods={"POST"})
     */
    public function save(
        Request $request
        )
    {
        try
        {
            //retrieve post Body
            $data = $request->getContent();
            
            //new instance and deserialize json data
            $equips = new Equipement();
            $equips = $this->serializer->deserialize($data, Equipement::class, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['date']]);
            
            $cloneequips = clone $equips;

            $findequips= $this->em->getRepository(Equipement::class)->find($equips->getcequipement());
            if($findequips){
                $equips = $findequips;
                $equips->setlibequipement( $cloneequips->getlibequipement());
                $equips->setquantite( $cloneequips->getquantite());
                $equips->setdescription( $cloneequips->getdescription());
                $equips->setimageurl( $cloneequips->getimageurl());
            }

            if(!str_starts_with($equips->getimageurl(), $equips->getcequipement())){        
               
                $image = Utils::uploadimage(
                    $this->getParameter('kernel.project_dir'), 
                    $equips->getimageurl(),
                    $equips->getcequipement()
                );

                $equips->setimageurl($image);
            }
            $equips->setdate( (new \DateTime('now')));

            //db call
            $this->em->persist($equips);
            $this->em->flush();

            //test event dispatcher
            // $equipevent = new EquipementEvent($equips, $this->em);
            // $eventdisp->dispatch(EquipementEvent::NAME, $equipevent);

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
     * @Route("/equipement/deleteby/{nb}", name="delete_equipemenr", methods={"GET"})
     */
    public function deleteby($nb)
    {
        try
        {
            $this->em->getConnection()->beginTransaction();

            $qb=$this->em->createQueryBuilder()
                        ->select(['b'])
                        ->from('App\Entity\BonCommandeDetail','b')
                        ->where('b.cequipement = :nb')
                        ->setParameter('nb',$nb)
                        ->getQuery();

            $bon = $qb->getResult();

            if(count($bon) > 0){
                return $this->json(
                    json_decode(Utils::jresponce('NOTOK','EXISTS', "false"), true)
                );
            }

            $qr= $this->em->createQueryBuilder()
                    ->delete()
                    ->from('App\Entity\Equipement','b')
                    ->where('b.cequipement = :nb')
                    ->setParameter('nb', $nb)
                    ->getQuery();
            
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
     * @Route("/equipement/filterby/{nb}", name="filterby_equipemenr", methods={"GET"})
     */
    public function filterby($nb)
    {
        try
        {    
            switch($nb){
                case 'PU':
                    $query = "
                        select e.* 
                        from Equipement e 
                        left join ( select sum(b.quantite) s, b.cequipement 
                                    from bon_commande_detail b group by b.cequipement) bd 
                        on e.cequipement = bd.cequipement 
                        order by bd.s DESC";
                    break;
                case 'MU':
                    $query = "
                        select e.* 
                        from Equipement e 
                        left join ( select sum(b.quantite) s, b.cequipement 
                                    from bon_commande_detail b group by b.cequipement) bd 
                        on e.cequipement = bd.cequipement 
                        order by bd.s";
                    break;
                case 'PC':
                    $query = "
                        select e.* 
                        from Equipement e 
                        left join ( select count(b.cequipement) s, b.cequipement 
                                    from bon_commande_detail b group by b.cequipement) bd 
                        on e.cequipement = bd.cequipement 
                        order by bd.s DESC";
                    break;
                case 'Q':
                    $query = "
                        select e.* 
                        from Equipement e 
                        order by e.quantite DESC";
                    break;
                default:
                    $query = "
                        select e.* 
                        from Equipement e 
                        order by e.quantite";
                    break;
            }

            $req = $this->em->getConnection()->prepare($query);                
            $req->execute();
            $equips = $req->fetchAllAssociative();    
            
            $jsonequips = $this->serializer->serialize($equips, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','DONE', $jsonequips), true)
        );
    }

    /**
     * @Route("/equipement/search/{word}", name="search_equipement", methods={"GET"})
     */
    public function search(string $word)
    {
        try
        {
            $qb=$this->em->createQueryBuilder()
                ->select(['e'])
                ->from('App\Entity\Equipement','e')
                ->where("e.cequipement like :word1")
                ->orWhere("e.libequipement like :word2")
                ->setParameter('word1', "%".$word."%")
                ->setParameter('word2', "%".$word."%")
                ->orderBy('e.libequipement','ASC')
                ->getQuery();

            $equips = $qb->getResult();

            $jsonequips = $this->serializer->serialize($equips, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "false"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','Search', $jsonequips), true)
        );
    }
}
