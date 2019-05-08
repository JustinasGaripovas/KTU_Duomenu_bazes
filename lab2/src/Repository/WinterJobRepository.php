<?php

namespace App\Repository;

use App\Entity\WinterJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WinterJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method WinterJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method WinterJob[]    findAll()
 * @method WinterJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WinterJobRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WinterJob::class);
    }

    // /**
    //  * @return WinterJob[] Returns an array of WinterJob objects
    //  */
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
    public function findOneBySomeField($value): ?WinterJob
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
