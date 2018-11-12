<?php

namespace App\Repository;

use App\Entity\FloodedRoads;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FloodedRoads|null find($id, $lockMode = null, $lockVersion = null)
 * @method FloodedRoads|null findOneBy(array $criteria, array $orderBy = null)
 * @method FloodedRoads[]    findAll()
 * @method FloodedRoads[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FloodedRoadsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FloodedRoads::class);
    }

    /**
     * @return FloodedRoads[] Returns an array of FloodedRoads objects
     */
    public function findByUserName($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.CreatedBy = :val')
            ->setParameter('val', $value)
            ->orderBy('f.CreatedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?FloodedRoads
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
