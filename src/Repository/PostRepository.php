<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Метод для получения списка постов отсортированных по количеству комментариев
     *
     * @return array (массив сущностей постов)
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function findByCommentCount(): array
    {
        // Запрос для получения списка постов по количеству комментариев
        $sql = '
        SELECT p.id
        FROM post as p
        LEFT JOIN comment as c on p.id = c.post_id
        GROUP BY p.id
        ORDER BY count(c.id) DESC
        ';
        // Выполняем SQL запрос для получения постов
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resultFromQuery = $stmt->fetchAll();

        // Получаем репозиторий поста
        $repos = $this->getEntityManager()->getRepository('App:Post');
        $result = [];
        // Проходимся по массиву с полученными постами
        foreach ($resultFromQuery as $postRaw) {
            // Ищем посты с указанным ID
            $post = $repos->findOneBy(['id' => $postRaw['id']]);
            // Добавляем найденный пост в массив результата
            $result[] = $post;
        }

        // Возвращаем массив найденных постов
        return $result;
    }
}
