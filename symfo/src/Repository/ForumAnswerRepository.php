<?php

namespace App\Repository;

use App\Entity\ForumAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ForumAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumAnswer[]    findAll()
 * @method ForumAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumAnswerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ForumAnswer::class);
    }

    // /**
    //  * @return ForumAnswer[] Returns an array of ForumAnswer objects
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
    public function findOneBySomeField($value): ?ForumAnswer
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
