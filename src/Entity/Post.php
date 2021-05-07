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
 * Сущность поста
 *
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * Идентификатор поста
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Количество минут на прочтение статьи
     *
     * @Assert\Range(min="1", minMessage="Минимальное значение должно быть больше нуля!")
     * @ORM\Column(type="integer")
     */
    private ?int $timeOnRead;

    /**
     * Название статьи
     *
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title;

    /**
     * Содержимое статьи
     *
     * @ORM\Column(type="text")
     */
    private ?string $body;

    /**
     * Количество просмотров
     *
     * @Assert\Range(min="0", minMessage="Минимальное значение должно быть неотрицательным!")
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private ?int $countView;

    /**
     * Дата создания статьи
     *
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $dateOfCreation;

    /**
     * Создатель (владелец) статьи
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $owner;

    /**
     * Список комментариев к данному посту
     *
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post", orphanRemoval=true, cascade={"persist"})
     * @OrderBy({"dateOfCreation" = "DESC"})
     */
    private Collection $comments;

    /**
     * Конструктор сущности поста
     */
    public function __construct()
    {
        // Дату создания определяем как текущее время
        $this->dateOfCreation = new DateTime();
        // Изначальный массив комментариев создаем как пустой массив
        $this->comments = new ArrayCollection();
    }

    /**
     * Получаем идентификатор поста
     *
     * @return int|null (идентификатор поста)
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Получить время на прочтение данной статьи
     *
     * @return int|null (время прочтения данной статьи)
     */
    public function getTimeOnRead(): ?int
    {
        return $this->timeOnRead;
    }

    /**
     * Установить новое время на прочтение данной статьи
     *
     * @param int $timeOnRead (новое время на прочтение статьи)
     * @return $this (обновленная сущность поста)
     */
    public function setTimeOnRead(int $timeOnRead): self
    {
        $this->timeOnRead = $timeOnRead;

        return $this;
    }

    /**
     * Получение названия поста
     *
     * @return string|null (название поста)
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Установка нового названия для поста
     *
     * @param string $title (новое название для поста)
     * @return $this (обновленная сущность поста)
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Метод для увеличения счетчика просмотров на единицу
     */
    public function incrementCountView()
    {
        $currentCountView = $this->getCountView();
        if ($currentCountView) {
            $this->setCountView($currentCountView + 1);
        } else {
            $this->setCountView(1);
        }
    }

    /**
     * Получение количества просмотров поста
     *
     * @return int|null (количество просмотров поста)
     */
    public function getCountView(): ?int
    {
        return $this->countView;
    }

    /**
     * Установка нового значения количества просмотров
     *
     * @param int $countView (новое количество просмотров)
     * @return $this (обновленная сущность поста)
     */
    public function setCountView(int $countView): self
    {
        $this->countView = $countView;

        return $this;
    }

    /**
     * Получение даты создания поста
     *
     * @return DateTimeInterface|null (дата создания поста)
     */
    public function getDateOfCreation(): ?DateTimeInterface
    {
        return $this->dateOfCreation;
    }

    /**
     * Установка нового значения создания поста
     *
     * @param DateTimeInterface $dateOfCreation (новая дата создания поста)
     * @return $this (обновленная сущность поста)
     */
    public function setDateOfCreation(DateTimeInterface $dateOfCreation): self
    {
        $this->dateOfCreation = $dateOfCreation;

        return $this;
    }

    /**
     * Получение владельца поста
     *
     * @return User|null (владелец поста)
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * Установка нового владельца поста
     *
     * @param User|null $owner (новый владелец поста)
     * @return $this (обновленная сущность поста)
     */
    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Получение списка комментариев к данному посту
     *
     * @return Collection|Comment[] (список комментариев)
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Метод для добавления комментария к посту
     *
     * @param Comment $comment (комментарий для добавления)
     * @return $this (обновленная сущность поста)
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    /**
     * Метод для удаления комментария у поста
     *
     * @param Comment $comment (комментарий для удаления)
     * @return $this (обновленная сущность поста)
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * Метод для получения текста аннотации к посту
     *
     * @return string (текст аннотации к посту)
     */
    public function getAnnotation(): string
    {
        $body = strip_tags($this->getBody());
        if (strlen($body) > 250) {
            return substr($body, 0, 250) . '...';
        }
        return substr($body, 0, 250);
    }

    /**
     * Получение содержимого поста
     *
     * @return string|null (содержимое поста)
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Установка нового содержимого поста
     *
     * @param string $body (новое содержимое поста)
     * @return $this (обновленная сущность поста)
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Метод для преобразования сущности поста к строке
     *
     * @return string
     */
    public function __toString()
    {
        return 'ID: ' . $this->id . ' | ' . $this->title;
    }
}
