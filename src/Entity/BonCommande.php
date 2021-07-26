<?php

namespace App\Entity;

use App\Repository\BonCommandeRepository;
use DateTimeZone;
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $bvalid;

    public function getnboncommande(): ?int
    {
        return $this->nboncommande;
    }

    public function setnboncommande($nboncommande): self
    {
        $this->nboncommande = $nboncommande;

        return $this;
    }

    public function getdateboncommande()
    {
        return $this->dateboncommande;
    }

    public function setdateboncommande($dateboncommande): self
    {
        $this->dateboncommande =  \DateTime::createFromFormat("d/m/Y",  $dateboncommande,new DateTimeZone('Africa/Tunis'));

        return $this;
    }

    public function setdateboncommandedate($dateboncommande): self
    {
        $this->dateboncommande =  $dateboncommande;

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

    public function getmontant(): ?float
    {
        return floatval($this->montant);
    }

    public function setmontant($montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getetat()
    {
        return $this->etat;
    }

    public function setetat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getbvalid(): ?bool
    {
        return $this->bvalid;
    }

    public function setbvalid(bool $bvalid): self
    {
        $this->bvalid = $bvalid;

        return $this;
    }
}
