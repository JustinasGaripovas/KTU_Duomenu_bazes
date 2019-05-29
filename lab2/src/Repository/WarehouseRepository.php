<?php

namespace App\Repository;

use App\Entity\Warehouse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Warehouse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Warehouse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Warehouse[]    findAll()
 * @method Warehouse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Warehouse::class);
    }

    public function findForReport($from, $to)
    {
        return $this->createQueryBuilder('w')
            ->leftJoin('w.fk_subunit', 'fk_subunit', 'w.fk_subunit == fk_subunit.id')
            ->select('fk_subunit.id','fk_subunit.Name', 'SUM(w.Capacity) as capacity','SUM(w.CurrentCapacity) as currentCapacity')
            ->groupBy('fk_subunit.Name','fk_subunit.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findForReportWithSubunit($from, $to, $id)
    {
        return $this->createQueryBuilder('w')
            ->leftJoin('w.fk_subunit', 'fk_subunit', 'w.fk_subunit == fk_subunit.id')
            ->andWhere('w.fk_subunit = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }

}
