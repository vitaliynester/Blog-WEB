<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Сущность пользователя
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="Аккаунт с данным email уже существует!")
 */
class User implements UserInterface
{
    /**
     * Идентификатор пользователя
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Электронная почта пользователя
     *
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private ?string $email;

    /**
     * Роль пользователя (обычный пользователь или администратор)
     *
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * Хэш пароль пользователя
     *
     * @var string (захэшированный пароль)
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * Фамилия пользователя
     *
     * @ORM\Column(type="string", length=255)
     */
    private ?string $lastName;

    /**
     * Имя пользователя
     *
     * @ORM\Column(type="string", length=255)
     */
    private ?string $firstName;

    /**
     * Отчество пользователя
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $patronymic;

    /**
     * Список постов данного пользователя
     *
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="owner", orphanRemoval=true)
     */
    private Collection $posts;

    /**
     * Список комментариев данного пользователя
     *
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="owner", orphanRemoval=true)
     */
    private Collection $comments;

    /**
     * Конструктор сущности пользователь
     */
    public function __construct()
    {
        // Для постов и комментариев устанавливаем пустые массивы
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Метод для получения идентификатора пользователя
     *
     * @return int|null (идентификатор пользователя, если NULL - пользователь не находится в БД)
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Метод для получения электронной почта пользователя
     *
     * @return string|null (электронная почта пользователя, если NULL - пользователь не находится в БД)
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Метод для установки нового значения электронной почты
     *
     * @param string $email (новое значение для электронной почты пользователя)
     *
     * @return $this (сущность пользователя после указания электронной почты)
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Идентификатор пользователя по которому можно точно определить его (электронная почта)
     *
     * @return string (электронная почта пользователя)
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Получить массив с ролями пользователя (обычный пользователь или администратор)
     *
     * @return array (массив с ролями пользователя)
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Если у пользователя нет ролей, то будет установлено ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Метод для установки новой/новых ролей для пользователя
     *
     * @param array $roles (роли для пользователя)
     *
     * @return $this (сущность пользователя после указания новых ролей)
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Метод для получения хэша пароля
     *
     * @return string (хэш пароль пользователя)
     *
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * Метод для установки нового пароля для пользователя
     *
     * @param string $password (новый пароль пользователя)
     *
     * @return $this (сущность пользователя после указания нового пароля)
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Метод для получения соли, на данный момент не реализован
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @return string|null (соль для хэширования пароля или NULL)
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Метод для очистки значений полей формы указанных при регистрации, но не относящихся к сущности
     *
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }

    /**
     * Метод для получения отчества пользователя
     *
     * @return string|null (отчество пользователя, если NULL - отчество не указано)
     */
    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    /**
     * Метод для указания нового отчества пользователя
     *
     * @param string|null $patronymic (отчество пользователя, если NULL - отчество не указано)
     *
     * @return $this (сущность пользователя после указания нового отчества)
     */
    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * Метод для получения имени в комментарии
     *
     * @return string|null (имя пользователя в формате "Фамилия Имя")
     */
    public function getCommentName(): ?string
    {
        return self::getLastName() . ' ' . self::getFirstName();
    }

    /**
     * Метод для получения фамилии пользователя
     *
     * @return string|null (фамилия пользователя, NULL - фамилия не указана)
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Метод для установки фамилии пользователя
     *
     * @param string $lastName (новая фамилия пользователя)
     *
     * @return $this (сущность пользователя после указания новой фамилии)
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Метод для получения имени пользователя
     *
     * @return string|null (имя пользователя, если NULL - имя не указано)
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Метод для установки нового имени пользователя
     *
     * @param string $firstName (новое имя пользователя)
     *
     * @return $this (сущность пользователя после указания нового имени)
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Получение всех постов созданных пользователем
     *
     * @return Collection|Post[] (список постов пользователя)
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * Добавление нового поста пользователю
     *
     * @param Post $post (пост для добавления)
     *
     * @return $this (обновленная сущность пользователя)
     */
    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setOwner($this);
        }

        return $this;
    }

    /**
     * Удаление определенного поста у пользователя
     *
     * @param Post $post (пост для удаления)
     *
     * @return $this (обновленная сущность пользователя)
     */
    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            if ($post->getOwner() === $this) {
                $post->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * Получение списка комментариев пользователя
     *
     * @return Collection|Comment[] (список комментариев пользователя)
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Добавление нового комментария к пользователю
     *
     * @param Comment $comment (комментарий для добавления)
     *
     * @return $this (обновленная сущность пользователя)
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setOwner($this);
        }

        return $this;
    }

    /**
     * Удаление определенного комментария от пользователя
     *
     * @param Comment $comment (комментарий для удаления)
     *
     * @return $this (обновленная сущность пользователя)
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            if ($comment->getOwner() === $this) {
                $comment->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * Метод для преобразования пользователя к строке
     *
     * @return string|null (представление пользователя в виде строки)
     */
    public function __toString()
    {
        return $this->email;
    }
}
