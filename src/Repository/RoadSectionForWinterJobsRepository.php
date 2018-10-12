<?php

namespace App\Repository;

use App\Entity\RoadSectionForWinterJobs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RoadSectionForWinterJobs|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoadSectionForWinterJobs|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoadSectionForWinterJobs[]    findAll()
 * @method RoadSectionForWinterJobs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoadSectionForWinterJobsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RoadSectionForWinterJobs::class);
    }

//   /**
//   * @return RoadSectionForWinterJobs[] Returns an array of RoadSectionForWinterJobs objects
//   */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RoadSectionForWinterJobs
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
