<?php

namespace App\Repository;

use App\Entity\Producto;
use App\Entity\ProductoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Producto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Producto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Producto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Producto::class);
    }

    /**
     * @return Producto[]
     */
    public function findAll()
    {
        $qry = $this->createQueryBuilder('p')
            ->orderBy('p.nombre')
        ;
        return $qry->getQuery()->execute();
    }

    public function findPro($data)
    {
        $qry = $this->createQueryBuilder('p')
            ->orderBy('p.nombre')
        ;
        /* revisa el parametro q y busca en nombre y codigo fe bara los datos */
        if (array_key_exists('q', $data) && $data['q']) {
            $pam = explode(" ", $data['q']);
            foreach ($pam as $item) {
                $txt = "'%$item%'";
                $p1 = $qry->expr()->like('p.nombre', $txt);
                $p2 = $qry->expr()->like('p.codBarra', $txt);
                $P = $qry->expr()->orX($p1, $p2);
                $qry->andWhere($P);
            }

        }

        if (array_key_exists('tipo', $data) && $data['tipo']) {
            /** @var $tipo ProductoTipo */
            $tipo = $data['tipo'];
            // busca los hijos revursivamente
            $hijos = $tipo->getHijosR();
            //genera la lista de hijos
//            $hijos_ids = [];
//            foreach ($hijos as $item) {
//                $hijos_ids[] = $item->getId();
//            }
            $hijos_ids = array_map(function (ProductoTipo $o) {
                return $o->getId();
            }, $hijos);
            $qry->innerJoin('p.tipo', 't')
                // ->andWhere('t.id = :tid')
                // ->setParameter('tid', $data['tipo']->getId());
                ->andWhere($qry->expr()->in('t', array_unique($hijos_ids)))
            ;

        }

        if (array_key_exists('visible', $data) && $data['visible']) {
            $qry->andWhere('p.visible = 1');
        }

        ///var_dump($qry->getDQL());
        return $qry->getQuery()->execute();
    }

    /*
     * Devuelve la lista de productos
     * return Producto[]
     */
    public function findStock($arr)
    {
        $qry = $this->createQueryBuilder('p')
            ->orderBy('p.nombre')
        ;

        if ($arr['xx']) {
            $qry->innerJoin('p.movimientos', 'm');
            if (array_key_exists('desde', $arr) && $arr['desde']) {
                $qry->andWhere('m.createAt >= :desde')
                    ->setParameter('desde', $arr['desde'])
                ;
            }

            if (array_key_exists('hasta', $arr) && $arr['hasta']) {
                $qry->andWhere('m.createAt <= :hasta')
                    ->setParameter('hasta', $arr['hasta'])
                ;
            }
        }

        if (array_key_exists('tipo', $arr) && $arr['tipo']) {
            $qry->andWhere('p.tipo = :tipo')
                ->setParameter('tipo', $arr['tipo'])
            ;
        }
        return $qry->getQuery()->execute();
    }
}
