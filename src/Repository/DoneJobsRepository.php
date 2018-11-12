<?php

namespace App\Repository;

use App\Entity\DoneJobs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DoneJobs|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoneJobs|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoneJobs[]    findAll()
 * @method DoneJobs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoneJobsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DoneJobs::class);
    }


    /**
     * @return DoneJobs[] Returns an array of Inspection objects
     */

    public function findByUserName($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.Username = :val')
            ->setParameter('val', $value)
            ->orderBy('i.Date', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

}
