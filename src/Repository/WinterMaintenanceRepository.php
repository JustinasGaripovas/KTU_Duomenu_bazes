<?php

namespace App\Repository;

use App\Entity\WinterMaintenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WinterMaintenance|null find($id, $lockMode = null, $lockVersion = null)
 * @method WinterMaintenance|null findOneBy(array $criteria, array $orderBy = null)
 * @method WinterMaintenance[]    findAll()
 * @method WinterMaintenance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WinterMaintenanceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WinterMaintenance::class);
    }

//    /**
//     * @return WinterMaintenance[] Returns an array of WinterMaintenance objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WinterMaintenance
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
