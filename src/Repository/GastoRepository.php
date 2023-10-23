<?php

namespace App\Repository;

use App\Entity\Gasto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Gasto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gasto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gasto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GastoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Gasto::class);
    }


    public function findAll (){
        $qry = $this->createQueryBuilder('g')
            ->orderBy('g.id', 'DESC');
     return $qry->getQuery()->execute();
    }

    public function findFiltro($data)
    {
        $tabla = 'g';
        $qry = $this->createQueryBuilder($tabla);
        if (array_key_exists('descripcion', $data) && $data['descripcion']) {
            $aa = '%' .$data['descripcion'].'%';
            $qry->andWhere($tabla.'.descripcion like :des')
                ->setParameter('des',$aa );
        }

        if (array_key_exists('desde', $data) && $data['desde']) {
            $qry->andWhere($tabla.'.fecha >= :desde')
                ->setParameter('desde', $data['desde']);
        }

        if (array_key_exists('hasta', $data) && $data['hasta']) {
            $qry->andWhere($tabla.'.fecha < :hasta')
                ->setParameter('hasta', $data['hasta']);
        }
        if (array_key_exists('cajero', $data) && $data['cajero']) {
            $qry->andWhere($tabla . '.createBy = :caj')
                ->setParameter('caj', $data['cajero']);
        }

        return $qry->getQuery()->execute();


    }

//    /**
//     * @return Gasto[] Returns an array of Gasto objects
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
    public function findOneBySomeField($value): ?Gasto
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
