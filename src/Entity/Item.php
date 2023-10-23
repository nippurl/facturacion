<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item extends Base
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Documento
     * @ORM\ManyToOne(targetEntity="App\Entity\Documento", inversedBy="items")
     */
    private $documento;

    /**
     * @var Producto
     * @ORM\ManyToOne(targetEntity="App\Entity\Producto", inversedBy="items")
     */
    private $producto;

    /**
     * @var String
     * @ORM\Column(type="string", length=20)
     */
    private $codigo;
    /**
     * @var string
     * @ORM\Column(type="string", length=200)
     */
    private $descripcion;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $cantidad;


    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $precioU;
    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="items")
     */
    private $vendedor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Movimiento", mappedBy="item", cascade={"all"})
     */
    private $movimientos;

    /**
     * Item constructor.
     * @param Documento $doc
     * @param Producto|null $pro
     * @param int $cant
     */

    public function __construct(Documento $doc, Producto $pro = null, $cant = 1)
    {
        parent::__construct();
        $this->documento = $doc;
        if ($pro) {
            $this->producto = $pro;
            $this->descripcion = $pro->getDetalle();
            $this->precioU = $pro->getPrecio();
            $this->calcular();

        }
        $this->cantidad = $cant;

        if ($doc->ultItem()) {
            $this->vendedor = $doc->ultItem()->getVendedor();

        } else {
            $this->vendedor = Usuario::Actual();
        }
        $this->movimientos = new ArrayCollection();

    }

    public function calcular()
    {
        $xx = $this->precioU * $this->cantidad;

        $this->total = $xx;


        return;
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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Item
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return Item
     */
    public function setDocumento(Documento $documento): Item
    {
        $this->documento = $documento;
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
     * @return Item
     */
    public function setProducto(Producto $producto): Item
    {
        $this->producto = $producto;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     * @return Item
     */
    public function setDescripcion($descripcion): Item
    {
        $this->descripcion = $descripcion;
        return $this;
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
     * @return Item
     */
    public function setCantidad(float $cantidad): Item
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrecioU(): ?float
    {
        return $this->precioU;
    }

    /**
     * @param float $precioU
     * @return Item
     */
    public function setPrecioU(?float $precioU): Item
    {
        $this->precioU = $precioU;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param float $total
     * @return Item
     */
    public function setTotal(float $total): Item
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return String
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param String $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function getDocId()
    {
        $xx = null;
        if ($this->documento) {
            $xx = $this->documento->getId();
        }
        return $xx;
    }

    public function setDocId($Did)
    {
        global $kernel;
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $container = $kernel->getContainer();
        /* @var $container  \Symfony\Component\DependencyInjection\Container */
        $em = $container->get('doctrine.orm.default_entity_manager');
        $doc = $em->find(Documento::class, $Did);
        $this->setDocumento($doc);
        return $this;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    /**
     *
     */
   public function setMovimientos ($movimientos){
       foreach ($movimientos as $movimiento) {
           $this->addMovimiento($movimiento);
       }
    return $this;
   }

    /**
     * @return Collection|Movimiento[]
     */
    public function getMovimientos(): Collection
    {
        return $this->movimientos;
    }

    public function addMovimiento(Movimiento $movimiento): self
    {
        if (!$this->movimientos->contains($movimiento)) {
            $this->movimientos[] = $movimiento;
            $movimiento->setItem($this);
        }

        return $this;
    }

    public function removeMovimiento(Movimiento $movimiento): self
    {
        if ($this->movimientos->contains($movimiento)) {
            $this->movimientos->removeElement($movimiento);
            // set the owning side to null (unless already changed)
            if ($movimiento->getItem() === $this) {
                $movimiento->setItem(null);
            }
        }

        return $this;
    }

    // add your own fields
}
