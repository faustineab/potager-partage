<?php

namespace App\Repository;

use App\Entity\MarketOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MarketOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarketOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarketOffer[]    findAll()
 * @method MarketOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarketOfferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MarketOffer::class);
    }

    // /**
    //  * @return MarketOffer[] Returns an array of MarketOffer objects
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
    public function findOneBySomeField($value): ?MarketOffer
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
