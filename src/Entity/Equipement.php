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
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $admissiondate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $quantityused;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $expirationdate;

    /**
     * @ORM\Column(type="integer")
     */
    private $reference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdmissiondate(): ?string
    {
        return $this->admissiondate;
    }

    public function setAdmissiondate(string $admissiondate): self
    {
        $this->admissiondate = $admissiondate;

        return $this;
    }

    public function getQuantityused(): ?string
    {
        return $this->quantityused;
    }

    public function setQuantityused(string $quantityused): self
    {
        $this->quantityused = $quantityused;

        return $this;
    }

    public function getExpirationdate(): ?string
    {
        return $this->expirationdate;
    }

    public function setExpirationdate(string $expirationdate): self
    {
        $this->expirationdate = $expirationdate;

        return $this;
    }

    public function getReference(): ?int
    {
        return $this->reference;
    }

    public function setReference(int $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
}
