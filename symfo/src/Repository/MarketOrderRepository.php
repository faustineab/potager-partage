<?php

namespace App\Repository;

use App\Entity\MarketOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MarketOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarketOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarketOrder[]    findAll()
 * @method MarketOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarketOrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MarketOrder::class);
    }

    // /**
    //  * @return MarketOrder[] Returns an array of MarketOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MarketOrder
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
