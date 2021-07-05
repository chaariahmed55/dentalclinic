<?php

namespace App\Entity;

use App\Repository\BonCommandeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BonCommandeRepository::class)
 */
class BonCommande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $nboncommande;

    /**
     * @Assert\DateTime
    //  * @var string A "Y-m-d H:i:s" formatted value
     * @ORM\Column(type="date")
     */
    private $dateboncommande;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cfournisseur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raisonsocialefournisseur;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3)
     */
    private $montant;

    public function getnboncommande(): ?int
    {
        return $this->nboncommande;
    }

    // public function setnboncommande(?int $nboncommande): self
    // {
    //     $this->nboncommande = $nboncommande;

    //     return $this;
    // }

    public function getdateboncommande(): ?\DateTimeInterface
    {
        return $this->dateboncommande;
    }

    public function setdateboncommande(\DateTimeInterface $dateboncommande): self
    {
        $this->dateboncommande = $dateboncommande;

        return $this;
    }

    public function getcfournisseur(): ?string
    {
        return $this->cfournisseur;
    }

    public function setcfournisseur(?string $cfournisseur): self
    {
        $this->cfournisseur = $cfournisseur;

        return $this;
    }

    public function getraisonsocialefournisseur(): ?string
    {
        return $this->raisonsocialefournisseur;
    }

    public function setraisonsocialefournisseur(?string $raisonsocialefournisseur): self
    {
        $this->raisonsocialefournisseur = $raisonsocialefournisseur;

        return $this;
    }

    public function getmontant(): ?string
    {
        return $this->montant;
    }

    public function setmontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }
}