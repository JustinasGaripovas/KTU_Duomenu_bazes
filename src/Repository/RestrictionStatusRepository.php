<?php

namespace App\Repository;

use App\Entity\RestrictionStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RestrictionStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestrictionStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestrictionStatus[]    findAll()
 * @method RestrictionStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestrictionStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RestrictionStatus::class);
    }

//    /**
//     * @return RestrictionStatus[] Returns an array of RestrictionStatus objects
//     */
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
    public function findOneBySomeField($value): ?RestrictionStatus
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
