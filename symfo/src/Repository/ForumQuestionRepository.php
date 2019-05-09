<?php

namespace App\Repository;

use App\Entity\ForumQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ForumQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumQuestion[]    findAll()
 * @method ForumQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumQuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ForumQuestion::class);
    }

    // /**
    //  * @return ForumQuestion[] Returns an array of ForumQuestion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ForumQuestion
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
