<?php

namespace App\Entity;

use App\Repository\InterventionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=InterventionRepository::class)
 * normalizationContext={"groups"={"intervention"}},
 * denormalizationContext={"groups"={"intervention"}}
 */
class Intervention
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"intervention"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"intervention"})
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"intervention"})
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Fiche::class, inversedBy="interventions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"intervention"})
     */
    private $fiche;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

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
