<?php

namespace App\Repository;

use App\Entity\Agenda;
use App\Entity\AgendaArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Agenda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agenda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agenda[]    findAll()
 * @method Agenda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgendaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agenda::class);
    }

    // /**
    //  * @return Agenda[] Returns an array of Agenda objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Agenda
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param \DateTime $dia
     * @return Collection|Agenda[]
     */
    public function findByFechaArea(\DateTime $dia, AgendaArea $area = null): array
    {
        $qry = $this->createQueryBuilder('a')
            ->addSelect('c,p')
            ->innerJoin('a.contacto', 'c')
            ->innerJoin('a.productos','p')
            ->innerJoin('a.usuario', 'u')
            ->innerJoin('u.orden', 'o')
            ->innerJoin('o.agendaArea', 'aa')
            ->where('a.fecha = :fecha')
            ->setParameter('fecha', $dia);
        if ($area) {
            $qry->andWhere('aa.id = :aa')
                ->setParameter('aa', $area->getId());

        }
        $qry->orderBy('a.usuario')
            ->addOrderBy('a.hora');

        return $qry->getQuery()->execute();
    }

    public function borrar(\DateTime $fecha, $hora, \App\Entity\Usuario $usuario)
    {
        $ag = $this->findBy([
            'fecha' => $fecha,
                'hora' =>new \DateTime($hora),
            'usuario' => $usuario
        ]);


        foreach ($ag as $item) {
            $this->_em->remove($item);
        }
        $this->_em->flush();
    }
}
