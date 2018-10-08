<?php

namespace App\Repository;

use App\Entity\Choices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Choices|null find($id, $lockMode = null, $lockVersion = null)
 * @method Choices|null findOneBy(array $criteria, array $orderBy = null)
 * @method Choices[]    findAll()
 * @method Choices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoicesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Choices::class);
    }

//    /**
//     * @return Choices[] Returns an array of Choices objects
//     */

    public function findChoiceByChoiceId($value)
    {
        return $this->createQueryBuilder('c')
            ->select('c.Choice')
            ->andWhere('c.ChoiceId = :val')
            ->setParameter('val', $value)
            //->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

/*    public function findChoiceName($value)
    {
        return $this->createQueryBuilder('c')
            ->select('c.Choice')
            ->andWhere('c.ChoiceId = :val')
            ->setParameter('val', $value)
            //->orderBy('c.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getScalarResult();
    }*/

    /*
    public function findOneBySomeField($value): ?Choices
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
