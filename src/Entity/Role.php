<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 * normalizationContext={"groups"={"role","user","fiche","article","commentaire","rendezvous"}},
 * denormalizationContext={"groups"={"role","user","fiche","article","commentaire","rendezvous"}}
 * 
 */
class Role
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"role","user","fiche","article","commentaire","rendezvous"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"role","user","fiche","article","commentaire","rendezvous"})
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="role", orphanRemoval=true)
     */
    private $users;


    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getRole() === $this) {
                $user->setRole(null);
            }
        }

        return $this;
    }

    
}
