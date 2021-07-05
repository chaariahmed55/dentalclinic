<?php

namespace App\CustomEvent;

use App\Entity\Equipement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Event;

class EquipementEvent extends Event
{
    public const NAME = 'Equipement.edit';
    private $em;
    protected $equipement;

    public function __construct(
        Equipement $equipement,
        EntityManagerInterface $em
        )
    {
        $this->equipement = $equipement;
        $this->em = $em;
    }

    public function changeQuantite()
    {
        $this->equipement->setdescription("Event edit");
        $this->em->persist($this->equipement);
        $this->em->flush();
    }
}

?>