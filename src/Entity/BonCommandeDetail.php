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
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity=BonCommande::class)
     * @ORM\JoinColumn(nullable=false, name="nboncommande", referencedColumnName="nboncommande")
     */
    private $nboncommande;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=50)
     * @ORM\ManyToOne(targetEntity=Equipement::class)
     * @ORM\JoinColumn(nullable=false, name="cequipement", referencedColumnName="cequipement")
     */
    private $cequipement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libequipement;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $ordre;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3)
     */
    private $prix;

    public function getnboncommande(): ?int
    {
        return $this->nboncommande;
    }

    public function setnboncommande(int $nboncommande): self
    {
        $this->nboncommande = $nboncommande;

        return $this;
    }

    public function getcequipement(): ?string
    {
        return $this->cequipement;
    }

    public function setcequipement(string $cequipement): self
    {
        $this->cequipement = $cequipement;

        return $this;
    }

    public function getlibequipement(): ?string
    {
        return $this->libequipement;
    }

    public function setlibequipement(?string $libequipement): self
    {
        $this->libequipement = $libequipement;

        return $this;
    }

    public function getordre(): ?int
    {
        return $this->ordre;
    }

    public function setordre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getquantite(): ?int
    {
        return $this->quantite;
    }

    public function setquantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getprix(): ?float
    {
        return (float)$this->prix;
    }

    public function setprix($prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
