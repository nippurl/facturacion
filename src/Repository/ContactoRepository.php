<?php

namespace App\Repository;

use App\Entity\Contacto;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Contacto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contacto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contacto[]    findAll()
 * @method Contacto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contacto::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('c')
            ->where('c.something = :value')->setParameter('value', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    /**
     * @param string $q
     * @param int $cant cantidad maxima
     * @return Contacto[]
     */
    public function findByApenom(string $q, int $cant = 50)
    {

        $qry = $this->createQueryBuilder('c')
            ->setMaxResults($cant)
            ->orderBy("c.razon")
           ;
$table ='c';

        self::filtroQ($qry, $table, $q);


        return $qry->getQuery()->execute();
    }

    /**
     * Busca por fecha de cumplaños
     * @param \DateTime $desde
     * @param $hasta
     * @return Contacto[]
     */
    public function findBycumple( DateTime $desde, DateTime $hasta)
    {
        $d = $desde->format("m-d");
        $h = $hasta->format("m-d");
        
        $qry= $this->createQueryBuilder('c')
        ->where('c.fecha_nac is not null')
        ->andWhere("DATE_FORMAT(c.fecha_nac,'%m-%d') >= :desde")
            ->andWhere("DATE_FORMAT(c.fecha_nac,'%m-%d') <= :hasta")
            ->setParameter('desde', $d)
            ->setParameter('hasta', $h)
            ->orderBy("DATE_FORMAT(c.fecha_nac,'%m-%d')");
            
        
            return $qry->getQuery()->execute();
    }

    /**
     * @param DateTime $desde
     * @return Contacto[]
     */
    public function nuevosClientes(DateTime $desde)
    {
        $qry= $this->createQueryBuilder('c')
            ->andWhere("c.createAt >= :desde")
            ->setParameter('desde', $desde);


        return $qry->getQuery()->execute();
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qry
     * @param string $table Nombre de la tabla
     * @param string $q Filtro a buscar
     */
  static  public function filtroQ(\Doctrine\ORM\QueryBuilder $qry, string $table, string $q): void
    {
        $i = 1;
        $arr = explode(' ', $q);
        if (!empty($arr)) {
            foreach ($arr as $t) {
                $i++;
                if (!empty($t)) {
                    $V = "P$i";
                    $txt = " $table.razon like :$V or $table.cuil like :$V or $table.observaciones like :$V ";
                    $qry->andWhere($txt)
                        ->setParameter($V, "%$t%");
                }
            }
        }
    }
}
