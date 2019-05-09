<?php

namespace App\Repository;

use App\Entity\GardenUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GardenUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method GardenUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method GardenUser[]    findAll()
 * @method GardenUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GardenUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GardenUser::class);
    }

    // /**
    //  * @return GardenUser[] Returns an array of GardenUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GardenUser
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
