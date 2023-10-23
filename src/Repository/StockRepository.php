<?php

namespace App\Repository;

use App\Entity\Producto;
use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    /**
     * @param Producto $producto
     * return Stock
     */
    public function findProducto(Producto $producto)
    {
        $qry = $this->createQueryBuilder('s')
            ->innerJoin('s.deposito', 'd')
            ->andWhere('s.producto = :pro')
            ->setParameter('pro', $producto)
            ->orderBy('d.orden');

        return $qry->getQuery()->execute();
    }

    /**
     * Crear el stock 0 en todos depositos para todos los productos
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function control (){

        $sql = 'INSERT ignore  INTO `stock`(`id`, `deposito_id`, `producto_id`, `cantidad`)
select null, d.id,p.id ,0 from producto p , deposito d ;';
        $this->_em->getConnection()->exec($sql);
     return true;
    }
}
