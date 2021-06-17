<?php

namespace App\Entity;

use App\Repository\BonCommandeDetailRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BonCommandeDetailRepository::class)
 */
class BonCommandeDetail
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
    private $NBonCommande;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $CEquipement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $LibEquipement;

    /**
     * @ORM\Column(type="integer")
     */
    private $Ordre;

    /**
     * @ORM\Column(type="integer")
     */
    private $Quantite;

    /**
     * @ORM\Column(type="float")
     */
    private $Prix;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNBonCommande(): ?int
    {
        return $this->NBonCommande;
    }

    public function setNBonCommande(int $NBonCommande): self
    {
        $this->NBonCommande = $NBonCommande;

        return $this;
    }

    public function getCEquipement(): ?string
    {
        return $this->CEquipement;
    }

    public function setCEquipement(string $CEquipement): self
    {
        $this->CEquipement = $CEquipement;

        return $this;
    }

    public function getLibEquipement(): ?string
    {
        return $this->LibEquipement;
    }

    public function setLibEquipement(?string $LibEquipement): self
    {
        $this->LibEquipement = $LibEquipement;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->Ordre;
    }

    public function setOrdre(int $Ordre): self
    {
        $this->Ordre = $Ordre;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->Quantite;
    }

    public function setQuantite(int $Quantite): self
    {
        $this->Quantite = $Quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }
}
