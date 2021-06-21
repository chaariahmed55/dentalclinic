<?php

namespace App\Entity;

use App\Repository\FicheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FicheRepository::class)
 * normalizationContext={"groups"={"fiche","medicament","intervention"}},
 * denormalizationContext={"groups"={"fiche","medicament","intervention"}}
 */
class Fiche
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"fiche","medicament","intervention"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fiche","medicament","intervention"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fiche","medicament","intervention"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Medicament::class, mappedBy="fiche", orphanRemoval=true)
     */
    private $medicaments;

    /**
     * @ORM\OneToMany(targetEntity=Intervention::class, mappedBy="fiche", orphanRemoval=true)
     */
    private $interventions;

    public function __construct()
    {
        $this->medicaments = new ArrayCollection();
        $this->interventions = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Medicament[]
     */
    public function getMedicaments(): Collection
    {
        return $this->medicaments;
    }

    public function addMedicament(Medicament $medicament): self
    {
        if (!$this->medicaments->contains($medicament)) {
            $this->medicaments[] = $medicament;
            $medicament->setFiche($this);
        }

        return $this;
    }

    public function removeMedicament(Medicament $medicament): self
    {
        if ($this->medicaments->removeElement($medicament)) {
            // set the owning side to null (unless already changed)
            if ($medicament->getFiche() === $this) {
                $medicament->setFiche(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Intervention[]
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): self
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions[] = $intervention;
            $intervention->setFiche($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning side to null (unless already changed)
            if ($intervention->getFiche() === $this) {
                $intervention->setFiche(null);
            }
        }

        return $this;
    }
}
