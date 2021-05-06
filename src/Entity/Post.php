<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Range(min="1", minMessage="Минимальное значение должно быть больше нуля!")
     * @ORM\Column(type="integer")
     */
    private ?int $timeOnRead;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $body;

    /**
     * @Assert\Range(min="0", minMessage="Минимальное значение должно быть неотрицательным!")
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private ?int $countView;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $dateOfCreation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $owner;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post", orphanRemoval=true, cascade={"persist"})
     * @OrderBy({"dateOfCreation" = "DESC"})
     */
    private Collection $comments;

    public function __construct()
    {
        $this->dateOfCreation = new DateTime();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeOnRead(): ?int
    {
        return $this->timeOnRead;
    }

    public function setTimeOnRead(int $timeOnRead): self
    {
        $this->timeOnRead = $timeOnRead;

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

    public function incrementCountView()
    {
        $currentCountView = $this->getCountView();
        if ($currentCountView) {
            $this->setCountView($currentCountView + 1);
        } else {
            $this->setCountView(1);
        }
    }

    public function getCountView(): ?int
    {
        return $this->countView;
    }

    public function setCountView(int $countView): self
    {
        $this->countView = $countView;

        return $this;
    }

    public function getDateOfCreation(): ?DateTimeInterface
    {
        return $this->dateOfCreation;
    }

    public function setDateOfCreation(DateTimeInterface $dateOfCreation): self
    {
        $this->dateOfCreation = $dateOfCreation;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getAnnotation()
    {
        $body = strip_tags($this->getBody());
        if (strlen($body) > 250) {
            return substr($body, 0, 250) . '...';
        }
        return substr($body, 0, 250);
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}
