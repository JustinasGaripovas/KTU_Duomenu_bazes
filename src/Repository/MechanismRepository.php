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

//    /**
//     * @return Mechanism[] Returns an array of Mechanism objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mechanism
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findBySubunitField($value)
    {
        return $this->createQueryBuilder('m')
            ->select('m.Number', 'm.Type')
            ->andWhere('m.Subunit = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
