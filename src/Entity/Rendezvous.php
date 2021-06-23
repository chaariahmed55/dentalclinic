<?php

namespace App\Entity;

use App\Repository\RendezvousRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RendezvousRepository::class)
 * normalizationContext={"groups"={"rendezvous"}},
 * denormalizationContext={"groups"={"rendezvous"}}
 */
class Rendezvous
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"rendezvous"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"rendezvous"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"rendezvous"})
     */
    private $dateadmission;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"rendezvous"})
     */
    private $datenext;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rendezvouses")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"rendezvous"})
     */
    private $user;

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

    public function getDateadmission(): ?string
    {
        return $this->dateadmission;
    }

    public function setDateadmission(string $dateadmission): self
    {
        $this->dateadmission = $dateadmission;

        return $this;
    }

    public function getDatenext(): ?string
    {
        return $this->datenext;
    }

    public function setDatenext(string $datenext): self
    {
        $this->datenext = $datenext;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }
}
