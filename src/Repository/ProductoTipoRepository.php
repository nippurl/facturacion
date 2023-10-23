<?php

namespace App\Repository;

use App\Entity\ProductoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductoTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductoTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductoTipo[]    findAll()
 * @method ProductoTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductoTipo::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.something = :value')->setParameter('value', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
