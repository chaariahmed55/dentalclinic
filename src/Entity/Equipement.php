<?php

namespace App\Entity;

use App\Repository\EquipementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipementRepository::class)
 */
class Equipement
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=50)
     */
    private $cequipement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libequipement;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $imageurl;

    /**
     * Get the value of cequipement
     */ 
    public function getcequipement()
    {
        return $this->cequipement;
    }

    /**
     * Set the value of cequipement
     *
     * @return  self
     */ 
    public function setcequipement($cequipement)
    {
        $this->cequipement = $cequipement;

        return $this;
    }

    /**
     * Get the value of libequipement
     */ 
    public function getlibequipement()
    {
        return $this->libequipement;
    }

    /**
     * Set the value of libequipement
     *
     * @return  self
     */ 
    public function setlibequipement($libequipement)
    {
        $this->libequipement = $libequipement;

        return $this;
    }

    /**
     * Get the value of quantite
     */ 
    public function getquantite()
    {
        return $this->quantite;
    }

    /**
     * Set the value of quantite
     *
     * @return  self
     */ 
    public function setquantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getdescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setdescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getdate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setdate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getimageurl(): ?string
    {
        return $this->imageurl;
    }

    public function setimageurl(?string $imageurl): self
    {
        $this->imageurl = $imageurl;

        return $this;
    }
}
