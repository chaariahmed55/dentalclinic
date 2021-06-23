<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 * normalizationContext={"groups"={"commentaire"}},
 * denormalizationContext={"groups"={"commentaire"}}
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"commentaire"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"commentaire"})
     */
    private $comment;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"commentaire"})
     */
    private $likecomment;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"commentaire"})
     */
    private $dislikecomment;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="comment")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"commentaire"})
     */
    private $article;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getLikecomment(): ?int
    {
        return $this->likecomment;
    }

    public function setLikecomment(int $likecomment): self
    {
        $this->likecomment = $likecomment;

        return $this;
    }

    public function getDislikecomment(): ?int
    {
        return $this->dislikecomment;
    }

    public function setDislikecomment(int $dislikecomment): self
    {
        $this->dislikecomment = $dislikecomment;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
