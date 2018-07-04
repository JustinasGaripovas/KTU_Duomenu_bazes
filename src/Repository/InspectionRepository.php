<?php

namespace App\Repository;

use App\Entity\Inspection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Inspection|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inspection|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inspection[]    findAll()
 * @method Inspection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InspectionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Inspection::class);
    }

//    /**
//     * @return Inspection[] Returns an array of Inspection objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Inspection
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
