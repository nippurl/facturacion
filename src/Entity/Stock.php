<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StockRepository")
 * @ORM\Table(name="stock",uniqueConstraints={ @ORM\UniqueConstraint(name="dep",columns={"deposito_id","producto_id"})})
 */
class Stock extends Base
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Deposito
     * @ORM\ManyToOne(targetEntity="App\Entity\Deposito", inversedBy="stock")
     */
    private $deposito;

    /**
     * @var Producto
     * @ORM\ManyToOne(targetEntity="App\Entity\Producto", inversedBy="stock")
     */

    private $producto;

    /**
     * @var int
     * @ORM\Column(type="float")
     */

    private $cantidad;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Stock
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Deposito
     */
    public function getDeposito(): ?Deposito
    {
        return $this->deposito;
    }

    /**
     * @param Deposito $deposito
     * @return Stock
     */
    public function setDeposito(Deposito $deposito): Stock
    {
        $this->deposito = $deposito;
        return $this;
    }

    /**
     * @return Producto
     */
    public function getProducto(): ?Producto
    {
        return $this->producto;
    }

    /**
     * @param Producto $producto
     * @return Stock
     */
    public function setProducto(Producto $producto): Stock
    {
        $this->producto = $producto;
        return $this;
    }

    /**
     * @return int
     */
    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    /**
     * @param int $cantidad
     * @return Stock
     */
    public function setCantidad(int $cantidad): Stock
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    public function descontar(float $mov_cant)
    {
        $this->cantidad -= $mov_cant;
    }


}
