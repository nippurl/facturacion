<?php

namespace App\Repository;

use App\Entity\DocumentoTipo;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DocumentoTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentoTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentoTipo[]    findAll()
 * @method DocumentoTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocumentoTipo::class);
    }

    /**
     * @param $user
     * @return DocumentoTipo[]
     */
    public function findMenu(Usuario $user)
    {
        $qry  = $this->createQueryBuilder('t')
            ->andWhere('t.menu is not null')
            ->orderBy('t.menu');
        if ($user->getBlanco()) {
            $qry->andWhere('t.blanco = 1');
        }

        return $qry->getQuery()->execute();
    }

    /**
     * Busca los accesos directos
     */
    public function findADirecto()
    {
        $qry = $this->createQueryBuilder('t')
            ->where('t.AD_orden is not null')
            ->orderBy('t.AD_orden');

        return $qry->getQuery()->execute();

    }
}
