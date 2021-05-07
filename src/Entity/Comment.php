<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность комментария для поста
 *
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * Идентификатор комментария
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Дата создания комментария
     *
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $dateOfCreation;

    /**
     * Текст комментария
     *
     * @ORM\Column(type="string", length=255)
     */
    private ?string $body;

    /**
     * Пост к которому оставляется комментарий
     *
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Post $post;

    /**
     * Пользователь оставивший комментарий
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $owner;

    /**
     * Конструктор сущности комментария
     */
    public function __construct()
    {
        // Дате создания указываем текущее время
        $this->dateOfCreation = new DateTime();
    }

    /**
     * Получение идетификатора комментария
     *
     * @return int|null (идентификатор комментария)
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Получение даты создания комментария
     *
     * @return DateTimeInterface|null (дата создания комментария)
     */
    public function getDateOfCreation(): ?DateTimeInterface
    {
        return $this->dateOfCreation;
    }

    /**
     * Установка новой даты создания комментария
     *
     * @param DateTimeInterface $dateOfCreation (новая дата создания комментария)
     * @return $this (обновленная сущность комментария)
     */
    public function setDateOfCreation(DateTimeInterface $dateOfCreation): self
    {
        $this->dateOfCreation = $dateOfCreation;

        return $this;
    }

    /**
     * Получение текста комментария
     *
     * @return string|null (текст комментария)
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Установка нового текста комментария
     *
     * @param string $body (новый текст комментария)
     * @return $this (обновленная сущность комментария)
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Получение поста к которому был установлен данный комментарий
     *
     * @return Post|null (пост с данным комментарием)
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * Установка нового поста
     *
     * @param Post|null $post (пост к которому привязываем комментарий)
     * @return $this (обновленная сущность комментария)
     */
    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Получение владельца комментария
     *
     * @return User|null (владелец комментария)
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * Установка нового владельца комментария
     *
     * @param User|null $owner (владелец комментария)
     * @return $this (обновленная сущность комментария)
     */
    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Метод приводящий сущность к строке
     *
     * @return string|null (сформированная строка сущности)
     */
    public function __toString()
    {
        return 'ID:' . $this->id . ' | POST:' . $this->post->getId() . ' | ' . $this->body;
    }
}
