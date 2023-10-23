<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovimientoRepository")
 * No se porque la fecha
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Movimiento extends Base
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var Producto
     * @ORM\ManyToOne(targetEntity="App\Entity\Producto", inversedBy="movimientos")
     */

   private $producto;

    /**
     * @var Deposito
     * @ORM\ManyToOne(targetEntity="App\Entity\Deposito", cascade={"all"})
     */

   private $origen;

    /**
     * @var Deposito
     * @ORM\ManyToOne(targetEntity="App\Entity\Deposito", cascade={"all"})
     */
   private $destino;

   /**
    * @var float
    * @ORM\Column(type="float")
    */


   private $cantidad;

    /**
     * @var float
     * @ORM\Column(type="float")
     */

   private $cantOrigen;

    /**
     * @var float
     * @ORM\Column(type="float")
     */

   private $cantDestino;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;
    /**
     * @var Usuario
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     */
    private $createdBy;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;
    /**
     * @var Usuario
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     */
    private $updatedBy;

    /**
     * @var Documento
     * @ORM\ManyToOne(targetEntity="App\Entity\Documento", inversedBy="movimientos")
     */
    private $documento;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Item", inversedBy="movimientos")
     */
    private $item;




    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return Producto
     */
    public function getProducto(): Producto
    {
        return $this->producto;
    }

    /**
     * @param Producto $producto
     */
    public function setProducto(Producto $producto): void
    {
        $this->producto = $producto;
    }

    /**
     * @return Deposito
     */
    public function getOrigen(): Deposito
    {
        return $this->origen;
    }

    /**
     * @param Deposito $origen
     */
    public function setOrigen(Deposito $origen): void
    {
        $this->origen = $origen;
    }

    /**
     * @return Deposito
     */
    public function getDestino(): Deposito
    {
        return $this->destino;
    }

    /**
     * @param Deposito $destino
     */
    public function setDestino(Deposito $destino): void
    {
        $this->destino = $destino;
    }

    /**
     * @return float
     */
    public function getCantidad(): float
    {
        return $this->cantidad;
    }

    /**
     * @param float $cantidad
     */
    public function setCantidad(float $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return float
     */
    public function getCantOrigen(): float
    {
        return $this->cantOrigen;
    }

    /**
     * @param float $cantOrigen
     */
    public function setCantOrigen(float $cantOrigen): void
    {
        $this->cantOrigen = $cantOrigen;
    }

    /**
     * @return float
     */
    public function getCantDestino(): float
    {
        return $this->cantDestino;
    }

    /**
     * @param float $cantDestino
     */
    public function setCantDestino(float $cantDestino): void
    {
        $this->cantDestino = $cantDestino;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Usuario
     */
    public function getCreatedBy(): Usuario
    {
        return $this->createdBy;
    }

    /**
     * @param Usuario $createdBy
     */
    public function setCreatedBy(Usuario $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Usuario
     */
    public function getUpdatedBy(): Usuario
    {
        return $this->updatedBy;
    }

    /**
     * @param Usuario $updatedBy
     */
    public function setUpdatedBy(Usuario $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return Documento
     */
    public function getDocumento(): Documento
    {
        return $this->documento;
    }

    /**
     * @param Documento $documento
     */
    public function setDocumento(Documento $documento): void
    {
        $this->documento = $documento;
    }


    public function __construct()
    {
        parent::__construct();
       // $this->item
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

}
