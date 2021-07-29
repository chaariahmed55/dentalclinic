<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * normalizationContext={"groups"={"user","fiche","article","commentaire","rendezvous"}},
 * denormalizationContext={"groups"={"user","fiche","article","commentaire","rendezvous"}}
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $password;

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
     * @ORM\OneToMany(targetEntity=Fiche::class, mappedBy="user", orphanRemoval=true)
     */
    private $fiche;

    /**
     * @ORM\OneToMany(targetEntity=Rendezvous::class, mappedBy="user", orphanRemoval=true)
     */
    private $rendezvous;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="user", orphanRemoval=true)
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user","fiche","article","commentaire","rendezvous"})
     */
    private $role;

    public function __construct()
    {
        $this->fiche = new ArrayCollection();
        $this->rendezvous = new ArrayCollection();
        $this->article = new ArrayCollection();
        $this->roles = array('ROLE_USER');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
     * @return Collection|rendezvous[]
     */
    public function getRendezvous(): Collection
    {
        return $this->rendezvous;
    }

    public function addRendezvou(rendezvous $rendezvou): self
    {
        if (!$this->rendezvous->contains($rendezvou)) {
            $this->rendezvous[] = $rendezvou;
            $rendezvou->setUser($this);
        }

        return $this;
    }

    public function removeRendezvou(rendezvous $rendezvou): self
    {
        if ($this->rendezvous->removeElement($rendezvou)) {
            // set the owning side to null (unless already changed)
            if ($rendezvou->getUser() === $this) {
                $rendezvou->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|article[]
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(article $article): self
    {
        if ($this->article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    public function getRole(): ?role
    {
        return $this->role;
    }

    public function setRole(?role $role): self
    {
        $this->role = $role;

        return $this;
    }
}
