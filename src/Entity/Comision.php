<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComisionRepository")
 * @ ORM\UniqueEntity(fields={"producto", "vendedor"})
 * @ORM\Table(uniqueConstraints={
 *      @ORM\UniqueConstraint(name="dimension_bitrate", columns={"producto_id", "vendedor_id"})
 * })
 */
class Comision extends Base
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Producto")
     * @ORM\JoinColumn(nullable=false)
     */
    private $producto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="comisiones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vendedor;

    /**
     * @ORM\Column(type="float")
     */
    private $comision;

    public function getId()
    {
        return $this->id;
    }

    public function getProducto(): ?Producto
    {
        return $this->producto;
    }

    public function setProducto(?Producto $producto): self
    {
        $this->producto = $producto;

        return $this;
    }

    public function getVendedor(): ?Usuario
    {
        return $this->vendedor;
    }

    public function setVendedor(?Usuario $vendedor): self
    {
        $this->vendedor = $vendedor;

        return $this;
    }

    public function getComision(): ?float
    {
        return $this->comision;
    }

    public function setComision(float $comision): self
    {
        $this->comision = $comision;

        return $this;
    }
    public function __toString (){
     return $this->producto . " de ". $this->vendedor;
    }
}

