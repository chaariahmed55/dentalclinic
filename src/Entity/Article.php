<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * normalizationContext={"groups"={"article","commentaire"}},
 * denormalizationContext={"groups"={"article","commentaire"}}
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"article","commentaire"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article","commentaire"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article","commentaire"})
     */
    private $imagepath;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article","commentaire"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article","commentaire"})
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="article", orphanRemoval=true)
     * 
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="article")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"article","commentaire"})
     */
    private $user;

    
    public function __construct()
    {
        $this->comment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getImagepath(): ?string
    {
        return $this->imagepath;
    }

    public function setImagepath(string $imagepath): self
    {
        $this->imagepath = $imagepath;

        return $this;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|commentaire[]
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(commentaire $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(commentaire $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    
}
