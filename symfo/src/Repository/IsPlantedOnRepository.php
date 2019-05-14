<?php

namespace App\Repository;

use App\Entity\IsPlantedOn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IsPlantedOn|null find($id, $lockMode = null, $lockVersion = null)
 * @method IsPlantedOn|null findOneBy(array $criteria, array $orderBy = null)
 * @method IsPlantedOn[]    findAll()
 * @method IsPlantedOn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IsPlantedOnRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IsPlantedOn::class);
    }

    // /**
    //  * @return IsPlantedOn[] Returns an array of IsPlantedOn objects
    //  */
    /*
    public function findByPlot($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IsPlantedOn
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
