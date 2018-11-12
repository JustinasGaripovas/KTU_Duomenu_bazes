<?php

namespace App\Repository;

use App\Entity\WinterJobs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WinterJobs|null find($id, $lockMode = null, $lockVersion = null)
 * @method WinterJobs|null findOneBy(array $criteria, array $orderBy = null)
 * @method WinterJobs[]    findAll()
 * @method WinterJobs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WinterJobsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WinterJobs::class);
    }

    /**
     * @return WinterJobs[] Returns an array of WinterJobs objects
     */

    public function findByUserName($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.CreatedBy = :val')
            ->setParameter('val', $value)
            ->orderBy('w.CreatedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?WinterJobs
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
