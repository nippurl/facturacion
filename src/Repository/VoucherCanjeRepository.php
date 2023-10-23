<?php

namespace App\Repository;

use App\Entity\VoucherCanje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VoucherCanje|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoucherCanje|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoucherCanje[]    findAll()
 * @method VoucherCanje[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherCanjeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoucherCanje::class);
    }

    // /**
    //  * @return VoucherCanje[] Returns an array of VoucherCanje objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VoucherCanje
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
