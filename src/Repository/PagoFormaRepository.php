<?php

namespace App\Repository;

use App\Entity\Pago;
use App\Entity\PagoForma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


/**
 * @method PagoForma|null find($id, $lockMode = null, $lockVersion = null)
 * @method PagoForma|null findOneBy(array $criteria, array $orderBy = null)
 * @method PagoForma[]    findAll()
 * @method PagoForma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PagoFormaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {

        parent::__construct($registry, PagoForma::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.something = :value')->setParameter('value', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * Genera isntancias de REpositorios con el class del objeto entity
     * @param $class
     * @return EntityRepository|\Doctrine\Persistence\ObjectRepository
     * @throws \Exception
     */
    static function getInstance($class)

    {
        global $kernel;
        if (is_null($kernel)) {
            // require_once '../..'.'/app/AppKernel.php';
            $kernel = new \App\Kernel('prod', false);
            $kernel->boot();
        }
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $container = $kernel->getContainer();
        /* @var $container  \Symfony\Component\DependencyInjection\Container */
        $em = $container->get('doctrine.orm.default_entity_manager');
        return $em->getRepository($class);
    }
}
