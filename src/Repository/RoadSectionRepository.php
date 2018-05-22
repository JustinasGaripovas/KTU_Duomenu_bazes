<?php

namespace App\Repository;

use App\Entity\RoadSection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RoadSection|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoadSection|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoadSection[]    findAll()
 * @method RoadSection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoadSectionRepository extends ServiceEntityRepository
{
    /**
     * RoadSectionRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RoadSection::class);
    }

    /**
     * @param $search
     * @param $unit_id
     * @param $subunit_id
     * @return RoadSection[] Returns an array of RoadSection objects
    */

    public function findRoadByNameOrIdField($search, $unit_id, $subunit_id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.SectionName LIKE :val or r.SectionId LIKE :val2')
            ->andWhere('r.UnitId = :val3 or r.UnitId = :val4')
            ->setParameter('val', '%'.$search.'%')
            ->setParameter('val2', '%'.$search.'%')
            ->setParameter('val3', $unit_id)
            ->setParameter('val4', $subunit_id)
            ->orderBy('r.SectionName', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?RoadSection
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
