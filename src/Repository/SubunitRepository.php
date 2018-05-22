<?php

namespace App\Repository;

use App\Entity\Subunit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Subunit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subunit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subunit[]    findAll()
 * @method Subunit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubunitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Subunit::class);
    }

//    /**
//     * @return Subunit[] Returns an array of Subunit objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Subunit
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
