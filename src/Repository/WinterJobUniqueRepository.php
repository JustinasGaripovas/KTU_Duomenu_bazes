<?php

namespace App\Repository;

use App\Entity\WinterJobUnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WinterJobUnique|null find($id, $lockMode = null, $lockVersion = null)
 * @method WinterJobUnique|null findOneBy(array $criteria, array $orderBy = null)
 * @method WinterJobUnique[]    findAll()
 * @method WinterJobUnique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WinterJobUniqueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WinterJobUnique::class);
    }

     /**
      * @return WinterJobUnique[] Returns an array of WinterJobUnique objects
      */
    public function deleteWithOriginal($originalId)
    {
        return $this->createQueryBuilder('w')
            ->delete()
            ->setParameter('val', $originalId)
            ->andWhere('w.OriginalId = :val')
            ->getQuery()
            ->getResult()
        ;
    }

    public function deleteAll()
    {
        return $this->createQueryBuilder('w')
            ->delete()
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByOriginalId($originalId)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.OriginalId = :val')
            ->setParameter('val', $originalId)
            ->getQuery()
            ->getResult()
        ;
    }



}
