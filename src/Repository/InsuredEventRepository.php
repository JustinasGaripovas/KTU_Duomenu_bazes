<?php

namespace App\Repository;

use App\Entity\InsuredEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InsuredEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method InsuredEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method InsuredEvent[]    findAll()
 * @method InsuredEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InsuredEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InsuredEvent::class);
    }

//    /**
//     * @return InsuredEvent[] Returns an array of InsuredEvent objects
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
    public function findOneBySomeField($value): ?InsuredEvent
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
