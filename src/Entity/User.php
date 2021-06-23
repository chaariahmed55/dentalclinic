<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * normalizationContext={"groups"={"user","fiche","article","commentaire","rendezvous"}},
 * denormalizationContext={"groups"={"user","fiche","article","commentaire","rendezvous"}}
 *
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $mdp;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $birthdate;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="user")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity=Fiche::class, mappedBy="user", orphanRemoval=true)
     */
    private $fiche;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="user", orphanRemoval=true)
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity=Rendezvous::class, mappedBy="user", orphanRemoval=true)
     */
    private $rendezvouses;

    public function __construct()
    {
        $this->fiche = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->rendezvouses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }

    public function setBirthdate(string $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|fiche[]
     */
    public function getFiche(): Collection
    {
        return $this->fiche;
    }

    public function addFiche(fiche $fiche): self
    {
        if (!$this->fiche->contains($fiche)) {
            $this->fiche[] = $fiche;
            $fiche->setUser($this);
        }

        return $this;
    }

    public function removeFiche(fiche $fiche): self
    {
        if ($this->fiche->removeElement($fiche)) {
            // set the owning side to null (unless already changed)
            if ($fiche->getUser() === $this) {
                $fiche->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rendezvous[]
     */
    public function getRendezvouses(): Collection
    {
        return $this->rendezvouses;
    }

    public function addRendezvouse(Rendezvous $rendezvouse): self
    {
        if (!$this->rendezvouses->contains($rendezvouse)) {
            $this->rendezvouses[] = $rendezvouse;
            $rendezvouse->setUser($this);
        }

        return $this;
    }

    public function removeRendezvouse(Rendezvous $rendezvouse): self
    {
        if ($this->rendezvouses->removeElement($rendezvouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezvouse->getUser() === $this) {
                $rendezvouse->setUser(null);
            }
        }

        return $this;
    }
}
