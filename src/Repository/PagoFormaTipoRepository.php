<?php

namespace App\Repository;

use App\Entity\PagoFormaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PagoFormaTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method PagoFormaTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method PagoFormaTipo[]    findAll()
 * @method PagoFormaTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PagoFormaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PagoFormaTipo::class);
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
