<?php

namespace App\Repository;

use App\Entity\GastoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GastoTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method GastoTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method GastoTipo[]    findAll()
 * @method GastoTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GastoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GastoTipo::class);
    }

//    /**
//     * @return GastoTipo[] Returns an array of GastoTipo objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GastoTipo
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
