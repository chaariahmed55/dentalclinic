<?php

namespace App\Controller;

use App\CustomEvent\EquipementEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Equipement;
use App\Utils\Utils;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EquipementController extends AbstractController
{
    private $em;
    private $serializer;

    public function __construct(
        EntityManagerInterface $em
        )
    {
        $this->em = $em;
        $this->serializer = new Serializer(array(new ObjectNormalizer()), array(new JsonEncoder()));
    }

    /**
     * @Route("/equipement/getall", name="all_equipement", methods={"GET"})
     */
    public function fetch()
    {
        try
        {
            $equips = $this->em->getRepository(Equipement::class)->findAll();

            $jsonequips = $this->serializer->serialize($equips, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "[]"), true)
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
            $equips = $this->em->getRepository(Equipement::class)->find($code);

            $jsonequips = $this->serializer->serialize($equips, 'json');

        }catch(\Exception $e)
        {
            return $this->json(
                json_decode(Utils::jresponce('ERROR', $e->getMessage(), "[]"), true)
            );
        }
        
        return $this->json(
            json_decode(Utils::jresponce('OK','FetchBy', $jsonequips), true)
        );
    }

    /**
     * @Route("/equipement/new", name="new_equipement", methods={"POST"})
     */
    public function add(
        Request $request,
        EventDispatcherInterface $eventdisp
        )
    {
        try
        {
            //retrieve post Body
            $data = $request->getContent();
            
            //new instance and deserialize json data
            $equips = new Equipement();
            $equips = $this->serializer->deserialize($data, Equipement::class, 'json');

            //db call
            $this->em->persist($equips);
            $this->em->flush();

            //test event dispatcher
            // $equipevent = new EquipementEvent($equips, $this->em);
            // $eventdisp->dispatch(EquipementEvent::NAME, $equipevent);

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
}
