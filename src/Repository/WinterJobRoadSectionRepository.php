<?php

namespace App\Repository;

use App\Entity\WinterJobRoadSection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WinterJobRoadSection|null find($id, $lockMode = null, $lockVersion = null)
 * @method WinterJobRoadSection|null findOneBy(array $criteria, array $orderBy = null)
 * @method WinterJobRoadSection[]    findAll()
 * @method WinterJobRoadSection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WinterJobRoadSectionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WinterJobRoadSection::class);
    }

//    /**
//     * @return WinterJobRoadSection[] Returns an array of WinterJobRoadSection objects
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
    public function findOneBySomeField($value): ?WinterJobRoadSection
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
