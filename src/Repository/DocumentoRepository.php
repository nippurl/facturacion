<?php

namespace App\Repository;

use App\Entity\Documento;
use App\Entity\DocumentoTipo;
use App\Entity\Pago;
use App\Entity\PagoForma;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Documento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Documento|null findOneBy(array $criteria, array $orderBy = null)
 * @ method Documento[]    findAll() redefinida
 * @method Documento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Documento::class);
    }


    /**
     *
     * @return
     */
    public function findAll()
    {
        return $this->getQueryBuilderBASE()->getQuery()->execute();
    }

    public function filtro($data)
    {
        $qry = $this->getQueryBuilderBASE();
        //self::QryFiltro($data, $qry, 'd');
        $tabla = 'd';
        /**
         * El filtro de fecha es por el pago de deudas, debe ser con fecha de movimientos de pago
         */
        $qry->innerJoin('d.pagos', 'p')
            ->addSelect('p');
        if (array_key_exists('desde', $data)) {
            $qry->andWhere('p.updateAt >= :desde')
                ->setParameter('desde', $data['desde']);
        }

        if (array_key_exists('hasta', $data)) {
            // Suma un dia para que tome el dia
            $dia = $data['hasta'];
            $dia->modify('+ 1 days');
            $qry->andWhere('p.updateAt <= :hasta')
                ->setParameter('hasta', $dia);
        }

        if (array_key_exists('tipo', $data)) {
            $arr = [];
            foreach ($data['tipo'] as $datum) {
                $arr[] = $datum->getId();
            }
            if (!empty($arr)) {
                $qry->andWhere($qry->expr()->in($tabla . 't.id', $arr));

            }
        }

        if (array_key_exists('cajero', $data) && $data['cajero']) {
            $qry->andWhere($tabla . '.createdBy = :caj')
                ->setParameter('caj', $data['cajero']);
        }
        $qry->orderBy('d.tipo')
            ->addOrderBy('d.numero');
        //var_dump($qry->getDQL());
        return $qry->getQuery()->execute();
    }

    /**
     * @param array $data
     * @param QueryBuilder $qry
     * @param string $tabla Nombre de la tabla
     * si filtra por tipo debe tener la configuracion $tabla.'t' la tabla tipo
     *
     */
    public static function QryFiltro($data, QueryBuilder $qry, $tabla): void
    {
        if (array_key_exists('desde', $data)) {
            $qry->andWhere($tabla . '.fecha >= :desde')
                ->setParameter('desde', $data['desde']);
        }

        if (array_key_exists('hasta', $data)) {
            $qry->andWhere($tabla . '.fecha <= :hasta')
                ->setParameter('hasta', $data['hasta']);
        }

        if (array_key_exists('tipo', $data)) {
            $arr = [];
            foreach ($data['tipo'] as $datum) {
                $arr[] = $datum->getId();
            }
            if (!empty($arr)) {
                $qry->andWhere($qry->expr()->in($tabla . 't.id', $arr));

            }
        }

        if (array_key_exists('cajero', $data) && $data['cajero']) {
            $qry->andWhere($tabla . '.createdBy = :caj')
                ->setParameter('caj', $data['cajero']);
        }

    }


    /**
     * @param array $data
     * @return array
     */
    public function findComiciones($data)
    {
        /***
         *
         *
         *select d.numero, d.id ,  u.nombre, group_concat(p.nombre), sum(c.comision * i.total) as suma
         * from documento d
         * inner join item i on d.id = i.documento_id
         * inner join producto p on i.producto_id = p.id
         * inner join usuario u on i.vendedor_id = u.id
         * inner join comision c on p.id = c.producto_id and c.vendedor_id=u.id
         *
         * where p.nombre like "%joico%"
         *
         *
         * group by d.id, u.id
         */

        $qry = $this->createQueryBuilder('d')
            //    ->addSelect('p')
            ///  ->addSelect('u')

            ->innerJoin('d.items', 'i')
            ->innerJoin('i.producto', 'p')
            ->innerJoin('i.vendedor', 'u')
            ->innerJoin('d.tipo', 'dt')
            ->innerJoin('d.contacto', 'cliente')

            ->where('d.estado =1');
        $p1 = $qry->expr()->eq('c.vendedor', 'i.vendedor');
        $p2 = $qry->expr()->eq('c.producto', 'i.producto');
        $p = $qry->expr()->andX($p1, $p2);
        $qry->innerJoin('u.comisiones', 'c', $p);

        $qry
            ->andWhere('c.comision >0')
            ->andWhere($p2)
            ->andWhere($p1)
            //   ->addSelect('i')

            ->select('dt.letra as letra')
            ->addSelect('d.id as d_id')
            ->addSelect('d.fecha as fecha')
            ->addSelect('d.numero as numero')
            ->addSelect('cliente.razon as cli')
            ->addSelect('u.nick as ven')
            ->addSelect('p.descripcion as desc')
            ->addSelect('i.total as total ')
            ->addSelect('c.comision as com')
            ->addSelect('d as doc')
            ->addSelect($qry->expr()->prod('i.total', 'c.comision') . ' as suma')
            ->groupBy('i.id')
            ->orderBy('d.numero')
            ->addOrderBy('i.id')//  ->addGroupBy('u.id');
        ;

        /***
         *  Agregar la formas de pago al sistema
         */
        $xx = "concat ( pagoTipo.nombre, '( ' , pagoss.monto , ') ' ) ";
        $qry
            ->innerJoin('d.pagos', 'pagoss')
            ->innerJoin('pagoss.forma','pagosForma')
            ->innerJoin('pagosForma.pagoFormaTipo' , 'pagoTipo')
            ->addSelect("group_concat( $xx ) as pagos ");
        /*
             *  Finaliza el agregado de sistema
             **/

        self::QryFiltro($data, $qry, 'd');
        if (array_key_exists('vendedor', $data)) {
            $ven = $data['vendedor'];
            if ($ven) {
                $qry->andWhere('u.id = :ven')
                    ->setParameter('ven', $ven->getId());
            }
        }
        if (array_key_exists('pagoForma', $data) && $data['pagoForma']) {
            $pagoFormas = $data['pagoForma'];
            $aa = [];
            foreach ($pagoFormas as $pagoForma) {
                $aa[]= $pagoForma->getId();
            }
            if (!empty($aa)) {
                $qry
                    //->innerJoin('d.pagos', 'pagos')
                    ->andWhere($qry->expr()->in('pagosForma.id',  ':pagos'))
                    ->setParameter('pagos', $aa);
            }
        }

        if (array_key_exists('tipoProducto', $data) && $data['tipoProducto']) {
            $qry->innerJoin('p.tipo', 'tp')
                ->andWhere('tp.id = :tprod')
                ->setParameter('tprod', $data['tipoProducto']->getId());
        }


       // dump($qry->getQuery()->getSQL());

        $re = $qry->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        /*return $resultArray;
        /**/
        //  $re = $qry->getQuery()->execute();
        //      dump($re);
        return $re;
    }

    /**
     * Busca por numero
     * @param int $q
     */
    public function findByNumero(int $q)
    {
        return $this->getQueryBuilderBASE()
            ->orderBy('d.fecha', 'DESC')
            ->where('d.numero = :num')
            ->setParameter('num', $q)
            ->getQuery()
            ->execute();
    }

    /**
     * Genera la lista de dudores que tienen
     * @param \DateTime|null $desde
     * @return Documento[]
     */
    public function findDeudores2($desde = null)
    {
        $qry = $this->getQueryBuilderBASE()
            ->innerJoin('d.pagos', 'p')
            ->innerJoin('p.forma', 'fp')
            ->innerJoin('fp.pagoFormaTipo', 'tp')
            //   ->where('tp.deuda != 0')
            ->groupBy('d.id')
            ->orderBy('d.fecha', 'ASC');
        $qry
            //->addSelect('sum(p.monto) as pagado')
            ->where('d.total > sum(p.monto)')
            ->orWhere('tp.deuda != 0');
        if ($desde) {
            $qry->andWhere('d.fecha >= :desde')
                ->setParameter('desde', $desde);
        }
        dump($qry->getDQL());
        return $qry->getQuery()
            ->execute();;


    }

    public function findDeudores($desde = null)
    {
        $sub = $this->_em->createQueryBuilder()
            ->select('sum(pagos.monto)')
            ->from(Pago::class, 'pagos')
            ->innerJoin('pagos.documento', 'doc')
            ->where('doc.id = d.id');
        $qry = $this->getQueryBuilderBASE()
            ->addSelect('p')
            ->innerJoin('d.pagos', 'p')
            ->innerJoin('p.forma', 'fp')
            ->innerJoin('fp.pagoFormaTipo', 'tp')
            //->andWhere('d.total < ('.$sub->getDQL() .')')
            //  ->orWhere('tp.deuda !=0')
            ->andWhere($sub->expr()->orX('d.total > (' . $sub->getDQL() . ')', 'tp.deuda !=0'));
        if ($desde) {
            $qry->andWhere('d.fecha >= :desde')
                ->setParameter('desde', $desde);
        }
        //    dump($qry->getDQL());
        return $qry->getQuery()
            ->execute();

    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilderBASE(): QueryBuilder
    {
        $qry = $this->createQueryBuilder('d')
            ->innerJoin('d.tipo', 'dt')
            ->innerJoin('d.contacto', 'c')
            ->addSelect('c')
            ->addSelect('dt')
            ->where('d.estado = 1');
        return $qry;
    }

    /**
     * @param Usuario $user
     * @param \DateTime $fecha
     * @return Documento[]
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function getCajaInicial(Usuario $user, \DateTime $fecha)
    {
        $em = $this->_em;
        $tipo = $em->find(DocumentoTipo::class, Documento::CAJA_INICIAL);
        $cajaInciales = $this->createQueryBuilder('d')
            ->where('d.tipo = :tipo')
            ->setParameter('tipo',$tipo)
            ->andWhere('d.fecha = :fecha')
            ->setParameter('fecha', $fecha)
            ->andWhere('d.createBy = :user')
            ->setParameter('user', $user)
        ->getQuery()
        ->execute();
        return $cajaInciales;

    }

}
