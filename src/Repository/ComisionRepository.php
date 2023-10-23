<?php

namespace App\Repository;

use App\Entity\Comision;
use App\Entity\Producto;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comision|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comision|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comision[]    findAll()
 * @method Comision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComisionRepository extends ServiceEntityRepository
{
    public const CANT = 50;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comision::class);
    }

    public function crearFaltantes()
    {
        $em = $this->_em;
        $sql = "INSERT ignore INTO `comision`(`id`, `producto_id`, `vendedor_id`, `comision`)
select null, p.id , v.id ,0 from producto p, usuario v";
        // $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

    }

    public function getCantFiltro ($data = []){
        $qry = $this->qryFiltro($data);
        $qry->select('count(c.id)');
        return $qry->getQuery()->getSingleScalarResult();
    }

    public function filtro($data = [], $page=1)
    {
        $cant =self::CANT;
        $qry = $this->qryFiltro($data);
        $qry->setMaxResults($cant);
        $qry->setFirstResult(($page-1)*$cant);





        return $qry->getQuery()->execute();
    }

    /**
     * @param $data
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function qryFiltro($data): \Doctrine\ORM\QueryBuilder
    {
        $qry = $this->createQueryBuilder('c')
            
            ->innerJoin('c.vendedor', 'v')
            ->innerJoin('c.producto', 'p')
            ->addSelect('v')
            ->addSelect('p');
        if (empty($data)) {
         $qry->where('1=0');
        }else {
            if (array_key_exists('producto', $data)) {
                $producto = $data['producto'];
                if ($producto) {
                    if ($producto instanceof Producto) {
                        $qry->andWhere('p.id = :prod')
                            ->setParameter('prod', $producto->getId());
                    } else {
                        $y = "'%$producto%'";
                        $qry->andWhere($qry->expr()->like('p.nombre', $y));
                    }
                }
            }
            if (array_key_exists('comision', $data) && $data['comision']) {
                $com = $data['comision'];
                $qry->andWhere('c.comision = :com')
                    ->setParameter('com', $com);
            }
            if (array_key_exists('vendedor', $data)) {
                $ven = $data['vendedor'];
                if ($ven) {
                    $qry->andWhere('v.id = :ven')
                        ->setParameter('ven', $ven->getId());
                }
            }
        }

        return $qry;
    }

    public function findComisiones($data)
    {

        $qry = $this->qryBaseFiltro($data);
        /// Conformar la lista de vendedores
        $vqry = clone $qry;
        $vqry->select('u as us')
            ->addSelect('sum(c.comision * i.total) as comision')
            ->groupBy('u')
            ->distinct();

        var_dump($vqry->getDQL());
        $vendedores = $vqry->getQuery()->execute();
        $qry->select('u.id as vid')
            ->addSelect('d.fecha as fecha')
            ->addSelect('(c.comision * i.total) as comision')
            ->orderBy('d.numero');

        $resultados = $qry->getQuery()->execute();

        $arr=[];
        $arr[0] = $vendedores;

        foreach ($resultados as $com){
            $fecha = $com['fecha']->format('d-m-Y');

            if (!array_key_exists($fecha, $arr)) {
                foreach ($vendedores as $ven){
                    $arr[$fecha][$ven['us']->getId()]=0;
                }
            }
            $arr[$fecha][$com['vid']] += $com['comision'];
        }

        return $arr;


    }

    /**
     * @param $data
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function qryBaseFiltro($data): \Doctrine\ORM\QueryBuilder
    {
        $qry = $this->_em->createQueryBuilder()
            ->from(Usuario::class, 'u')
            ->innerJoin('u.comisiones', 'c');
        //$qry = $this->createQueryBuilder('c')
        //    ->innerJoin('c.vendedor', 'u');
        $qry
            ->innerJoin('c.producto', 'p');
        $con = $qry->expr()->andx(
            $qry->expr()->eq('i.producto', 'p.id'),
            $qry->expr()->eq('i.vendedor', 'u.id')
        );
        $qry
            ->innerJoin('p.items', 'i', Join::WITH, $con)
            ->innerJoin('i.documento', 'd')
            ->innerJoin('d.tipo', 'dt')
            ->andWhere('d.estado = 1');
        DocumentoRepository::QryFiltro($data, $qry, 'd');
        if (array_key_exists('vendedor', $data) && $data['vendedor']) {
            $qry->andWhere('u.id = :ven')
                ->setParameter('ven', $data['vendedor']->getId());
        }
        return $qry;
    }

    /**
     * Filtra los documentos donde hay comisiones
     * @param $data
     * @return Documento
     */
    public function findExtendido ($data)
{


        $qry = $this->qryBaseFiltro($data);
    $qry->addSelect('d')
   // ->addSelect('i');
      //  ->groupBy('d')
;
    $qry->addSelect('u');
    $qry->andWhere('c.comision >0');
    dump($qry->getDQL());
     return $qry->getQuery()->execute();
    }

    public function cambiar(int $proId,int  $venId, float $com)
    {
        $comision = $this->findProVen($proId, $venId);
        if (!$comision) {
            $comision = new Comision();
            $pro = $this->_em->find(Producto::class, $proId);
            $comision->setProducto($pro);
            $ven = $this->_em->find(Usuario::class, $venId);
            $comision->setVendedor($ven);
        }
        $comision->setComision($com);
        $this->_em->persist($comision);
        $this->_em->flush();
    }

    /**
     * @param int $proId
     * @param int $venId
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return null|Comision
     */
    public function findProVen(int $proId, int $venId): ?Comision
    {
        return  $this->createQueryBuilder('c')
            ->innerJoin('c.producto', 'p')
            ->innerJoin('c.vendedor', 'v')
            ->where('p.id = :proId')
            ->setParameter('proId', $proId)
            ->andWhere('v.id = :venId')
            ->setParameter('venId', $venId)
            ->getQuery()->getOneOrNullResult();
    }

}
