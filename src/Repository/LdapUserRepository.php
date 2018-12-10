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

    /**
     * @return LdapUser[] Returns an array of LdapUser objects
     */

    public function findAllByName($value)
    {

        $r = $this->createQueryBuilder('l')
            ->andWhere('l.name = :val')
            ->setParameter('val','%'.$value.'%')
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllById($value)
    {

        $r = $this->createQueryBuilder('l')
            ->andWhere('l.Subunit = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        return $r;
    }



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

    public function findWithPages($offset, $limit, $subunit)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.Subunit = :val')
            ->setParameter('val', $subunit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findCount()
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->getQuery()
            ->getResult()
            ;
    }


}
