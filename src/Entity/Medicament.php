<?php

namespace App\Entity;

use App\Repository\MedicamentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MedicamentRepository::class)
 * normalizationContext={"groups"={"medicament"}},
 * denormalizationContext={"groups"={"medicament"}}
 */
class Medicament
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"medicament"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"medicament"})
     */
    private $quantiteparjour;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"medicament"})
     */
    private $quantitepardose;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"medicament"})
     */
    private $dure;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"medicament"})
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Fiche::class, inversedBy="medicaments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"medicament"})
     */
    private $fiche;

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

    public function getFiche(): ?fiche
    {
        return $this->fiche;
    }

    public function setFiche(?fiche $fiche): self
    {
        $this->fiche = $fiche;

        return $this;
    }
}
