<?php

namespace App\Entity;

use App\Repository\RendezvousRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RendezvousRepository::class)
 */
class Rendezvous
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dateadmission;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $datenext;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDateadmission(): ?string
    {
        return $this->dateadmission;
    }

    public function setDateadmission(string $dateadmission): self
    {
        $this->dateadmission = $dateadmission;

        return $this;
    }

    public function getDatenext(): ?string
    {
        return $this->datenext;
    }

    public function setDatenext(string $datenext): self
    {
        $this->datenext = $datenext;

        return $this;
    }
}
