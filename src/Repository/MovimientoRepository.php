<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\Movimiento;
use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Movimiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movimiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movimiento[]    findAll()
 * @method Movimiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovimientoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Movimiento::class);
    }

    /**
     * Genera le movimento de meraderia
     * @param $item
     */
    public function moverStock(Item $item): array
    {
        // buscar los depositos que hay ese producto
        /** @var StockRepository $SR */
        $SR = $this->_em->getRepository(Stock::class);
        /** @var Movimiento[] $movs */
        $movs =[];
        /** @var float $cant Cantidad a mover  */
        $cant=$item->getCantidad();
        /** @var Stock[] $stock Busca los stock disponibles */;
        $stocks = $SR->findProducto($item->getProducto());
        /// Se fija si en el deposito de la factura esta en lista, lo que elije ese
        $deposito = $item->getDocumento()->getDeposito();
        if ($deposito) {
            foreach ($stocks as $stock) {
                if ($deposito == $stock->getDeposito()) {
                    $mov_cant = min($cant, $stock->getCantidad());
                    $cant -= $mov_cant;
                    $stock->descontar($mov_cant);
                    $mov = new Movimiento();
                    $mov->setItem($item);
                    $mov->setProducto($item->getProducto());
                    $mov->setCantOrigen($mov_cant);
                    $mov->setCantDestino($mov_cant);
                    $mov->setCantidad($mov_cant);
                    $mov->setOrigen($stock->getDeposito());
                    $this->_em->persist($stock);
                    $movs[] = $mov;
                    break;
                }
            }

        }
        /** @var Stock $stock */
        foreach ($stocks as $stock) {
            $mov_cant = min($cant, $stock->getCantidad());
            $cant -= $mov_cant;
            $stock->descontar($mov_cant);
            $mov = new Movimiento();
            $mov->setItem($item);
            $mov->setProducto($item->getProducto());
            $mov->setCantOrigen($mov_cant);
            $mov->setCantDestino($mov_cant);
            $mov->setCantidad($mov_cant);
            $mov->setOrigen($stock->getDeposito());
            $this->_em->persist($stock);
            $movs[] = $mov;
            if (!$cant) {
                break;
            }
        }
        // Descontar en orden y generar el movimento
        return $movs;
    }
}
