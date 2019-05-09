<?php

namespace App\Repository;

use App\Entity\ForumTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ForumTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumTag[]    findAll()
 * @method ForumTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumTagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ForumTag::class);
    }

    // /**
    //  * @return ForumTag[] Returns an array of ForumTag objects
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
    public function findOneBySomeField($value): ?ForumTag
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
