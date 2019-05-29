<?php

namespace App\Repository;

use App\Entity\WinterJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WinterJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method WinterJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method WinterJob[]    findAll()
 * @method WinterJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WinterJobRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WinterJob::class);
    }


    public function findForReport($from, $to)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.StartedAt BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->leftJoin('w.people', 'people', 'w.people == people.id')
            ->leftJoin('people.fk_subunit', 'fk_subunit', 'people.fk_subunit == fk_subunit.id')
            ->select('fk_subunit.id','fk_subunit.Name','SUM(w.ActualCost) as actualSum','SUM(w.EstimatedCost) as estimatedSum')
            ->groupBy('fk_subunit.id','fk_subunit.Name')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWinterJobsBySubunit($from, $to, $id)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.StartedAt BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->leftJoin('w.people', 'people', 'w.people == people.id')
            ->leftJoin('people.fk_subunit', 'fk_subunit', 'people.fk_subunit == fk_subunit.id')
            ->andWhere('people.fk_subunit = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }

}
