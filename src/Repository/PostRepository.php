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
        SELECT p.id
        FROM post as p
        LEFT JOIN comment as c on p.id = c.post_id
        GROUP BY p.id
        ORDER BY count(c.id) DESC
        ';
        $conn = $this->getEntityManager()
            ->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $resultFromQuery = $stmt->fetchAll();
        $repos = $this->getEntityManager()->getRepository('App:Post');
        $result = [];
        foreach ($resultFromQuery as $postRaw) {
            $post = $repos->findOneBy(['id' => $postRaw['id']]);
            $result[] = $post;
        }

        return $result;
    }
}
