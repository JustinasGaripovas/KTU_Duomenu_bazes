<?php

namespace App\Repository;

use App\Entity\MaintenaceLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MaintenaceLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaintenaceLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaintenaceLevel[]    findAll()
 * @method MaintenaceLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaintenaceLevelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MaintenaceLevel::class);
    }

//    /**
//     * @return MaintenaceLevel[] Returns an array of MaintenaceLevel objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MaintenaceLevel
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
