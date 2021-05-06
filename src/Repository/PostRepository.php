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

    public function findByCommentCount(): array
    {
        $sql = '
        SELECT p.id, p.owner_id, p.time_on_read, p.title, p.body, p.count_view, p.date_of_creation
        FROM post as p
        LEFT JOIN comment as c on p.id = c.post_id
        GROUP BY p.id, p.owner_id, p.time_on_read, p.title, p.body, p.count_view, p.date_of_creation
        ORDER BY count(c.id) DESC
        ';
        $conn = $this->getEntityManager()
            ->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
