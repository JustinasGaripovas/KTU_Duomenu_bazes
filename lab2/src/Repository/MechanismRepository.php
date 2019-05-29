<?php

namespace App\Repository;

use App\Entity\Mechanism;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Mechanism|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mechanism|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mechanism[]    findAll()
 * @method Mechanism[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MechanismRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Mechanism::class);
    }


    public function findForReport($from,$to)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.LastChecked BETWEEN :from AND :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to)
            ->groupBy('m.fk_winterjob')
            ->select('COUNT(m.fk_winterjob) as count', 'm.VehicleCode', 'm.isUsable', 'm.VehicleType', 'm.id')
            ->getQuery()
            ->getResult()
        ;
    }

}
