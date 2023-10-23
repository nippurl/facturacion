<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    /**
     * Busca por Dni
     * @param string $dni
     *
     * @return Contacto|null
     */
    public function findDni($dni)
    {
        $qry = $this->createQueryBuilder('u')
            ->where('u.dni = :dni')
            ->setParameter('dni', $dni);
        return $qry->getQuery()->getOneOrNullResult();
    }

    /**
     * Busca por numero de Ente
     * @param $IDEnte
     * @return null|Contacto
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findEnte($IDEnte)
    {
        $qry = $this->createQueryBuilder('u')
            ->where('u.ente = :ente')
            ->setParameter('ente', $IDEnte);
        return $qry->getQuery()->getOneOrNullResult();
    }

    public function qryVisibles():QueryBuilder
    {
        return $this->createQueryBuilder('u')
            ->where('u.visible = 1')
            ->orderBy('u.nombre');
    }


}
