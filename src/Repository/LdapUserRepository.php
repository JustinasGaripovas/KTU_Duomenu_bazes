<?php

namespace App\Repository;

use App\Entity\LdapUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LdapUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method LdapUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method LdapUser[]    findAll()
 * @method LdapUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LdapUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LdapUser::class);
    }

//    /**
//     * @return LdapUser[] Returns an array of LdapUser objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findUnitIdByUserName($value): ?LdapUser
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.name = :val')
            ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

   /* public function findUserByUserName($value): ?LdapUser
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.name = :val')
            ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }*/

}
