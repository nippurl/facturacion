<?php

namespace App\Repository;

use App\Entity\Documento;
use App\Entity\Voucher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Voucher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voucher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voucher[]    findAll()
 * @method Voucher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voucher::class);
    }

    /**
     * @param $q Filtro ya sea numero o cliente o
     * @return Voucher[]
     */
    public function findAllINv($q=null)
    {
        $qry = $this->createQueryBuilder('v')
            ->orderBy('v.id','DESC');

        if ($q) {
            $qry ->innerJoin('v.canjes' ,'c')
                ->leftJoin(Documento::class, 'do', 'with','do.numero = c.comanda')
                ->leftJoin('do.contacto','co');
            ContactoRepository::filtroQ($qry,'co',$q);
            $qry->orWhere('v.numero =:q')

                ->orWhere('v.compra_numero = :q')
                ->setParameter('q', $q);
        }

         return $qry  ->getQuery()
            ->execute();
    }

    // /**
    //  * @return Voucher[] Returns an array of Voucher objects
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
    public function findOneBySomeField($value): ?Voucher
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
