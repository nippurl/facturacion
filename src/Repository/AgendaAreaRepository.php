<?php

namespace App\Repository;

use App\Entity\AgendaArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AgendaArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgendaArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgendaArea[]    findAll()
 * @method AgendaArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgendaAreaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgendaArea::class);
    }

    // /**
    //  * @return AgendaArea[] Returns an array of AgendaArea objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AgendaArea
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
