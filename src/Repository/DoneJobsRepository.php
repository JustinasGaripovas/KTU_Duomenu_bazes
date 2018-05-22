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

//    /**
//     * @return DoneJobs[] Returns an array of DoneJobs objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
  /**
    * @return DoneJobs[] Returns an array of DoneJobs objects by username
   */

    public function findAllByUserName($value): ?DoneJobs
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.Username = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getMaxResults()
        ;
    }

}
