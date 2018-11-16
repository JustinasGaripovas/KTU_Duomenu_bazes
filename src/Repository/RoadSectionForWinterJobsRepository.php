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

    public function findRoadByNameOrIdField($search, $unit_id, $subunit_id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.SectionId LIKE :val')
            ->andWhere('r.Subunit = :val3 or r.Subunit = :val4')
            ->setParameter('val', '%'.$search.'%')
            ->setParameter('val3', $unit_id)
            ->setParameter('val4', $subunit_id)
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findWithPages($offset, $limit)
    {
        return $this->createQueryBuilder('r')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
            ;
    }
    public function findCount()
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->getQuery()
            ->getResult()
            ;
    }

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
