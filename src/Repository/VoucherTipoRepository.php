<?php

namespace App\Repository;

use App\Entity\VoucherTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VoucherTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoucherTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoucherTipo[]    findAll()
 * @method VoucherTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoucherTipo::class);
    }

    // /**
    //  * @return VoucherTipo[] Returns an array of VoucherTipo objects
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
    public function findOneBySomeField($value): ?VoucherTipo
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
