<?php

namespace App\Repository;

use App\Entity\Deposito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Deposito|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deposito|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deposito[]    findAll()
 * @method Deposito[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepositoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Deposito::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('d')
            ->where('d.something = :value')->setParameter('value', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
