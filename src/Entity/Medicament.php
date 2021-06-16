<?php

namespace App\Entity;

use App\Repository\MedicamentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MedicamentRepository::class)
 */
class Medicament
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantiteparjour;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantitepardose;

    /**
     * @ORM\Column(type="integer")
     */
    private $dure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteparjour(): ?int
    {
        return $this->quantiteparjour;
    }

    public function setQuantiteparjour(int $quantiteparjour): self
    {
        $this->quantiteparjour = $quantiteparjour;

        return $this;
    }

    public function getQuantitepardose(): ?int
    {
        return $this->quantitepardose;
    }

    public function setQuantitepardose(int $quantitepardose): self
    {
        $this->quantitepardose = $quantitepardose;

        return $this;
    }

    public function getDure(): ?int
    {
        return $this->dure;
    }

    public function setDure(int $dure): self
    {
        $this->dure = $dure;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
}
