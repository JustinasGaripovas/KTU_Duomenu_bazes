<?php

namespace App\Repository;

use App\Entity\WinterJobRoads;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WinterJobRoads|null find($id, $lockMode = null, $lockVersion = null)
 * @method WinterJobRoads|null findOneBy(array $criteria, array $orderBy = null)
 * @method WinterJobRoads[]    findAll()
 * @method WinterJobRoads[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WinterJobRoadsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WinterJobRoads::class);
    }

//    /**
//     * @return WinterJobRoads[] Returns an array of WinterJobRoads objects
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
    public function findOneBySomeField($value): ?WinterJobRoads
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
