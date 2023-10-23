<?php

namespace App\Entity;

use DateTime as datetime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Producto extends Base
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var String
     * @ORM\Column(type="string", length=250, unique=true)
     */
    private $nombre;

    /**
     * @var String
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $descripcion;
    /**
     * @var String
     * @ORM\Column(type="string", length=500,unique=true, nullable=true)
     */
    private $codBarra;
    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * Lo que se Considera minima fraccion
     * @var float
     * @ORM\Column(type="float")
     */
    private $fraccion;

    /**
     * @var ProductoTipo[]
     * @ORM\ManytoOne(targetEntity="App\Entity\ProductoTipo", inversedBy="productos")
     */
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="producto")
     * @var Item[]
     */
    private $items;

    /**
     * @var Movimiento[]
     * @ORM\OneToMany(targetEntity="App\Entity\Movimiento", mappedBy="producto" )
     * @ ORM\OrderBy({"create_at","DESC"})
     */
    private $movimientos;

    /**
     * Precio de Costo
     * @ORM\Column(type="float" , nullable=true)
     * @var float
     */
    private $costo;

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return Movimiento[]
     */
    public function getMovimientos(): array
    {
        return $this->movimientos;
    }

    /**
     * @param Movimiento[] $movimientos
     */
    public function setMovimientos(array $movimientos): void
    {
        $this->movimientos = $movimientos;
    }

    /**
     * @return float
     */
    public function getCosto(): ?float
    {
        return $this->costo;
    }

    /**
     * @param float $costo
     */
    public function setCosto(float $costo): void
    {
        $this->costo = $costo;
    }


    /**
     * @param Stock $stock
     */
    public function addStock(Stock $stock)
    {
        $this->stock[] = $stock;
    }

    /**
     * @param Stock $stock
     */
    public function removeStock(Stock $stock)
    {
        if (false !== $key = array_search($stock, $this->stock, true)) {
            array_splice($this->stock, $key, 1);
        }
    }


    /**
     * @var Stock[]
     * @ORM\OneToMany(targetEntity="App\Entity\Stock", mappedBy="producto")
     * @ ORM\OrderBy({"orden"="ASC"})
     */
    private $stock;
    /**
     * @ORM\Column(type="boolean", options={"default"="1"})
     * si es false, No mueve stock es un servico
     * si es verdadero es que mueve stock y es un producto
     */
    private $tipoStock;

    public function __construct()
    {
        parent::__construct();
        $this->precio = 0;
        $this->fraccion = 1;
        $this->tipoStock = true;

    }

    /**
     * @return Stock[]
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param Stock[] $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function __toString()
    {
        $xx = $this->getCodBarra();
        if ($this->getCodBarra() && $this->getNombre()) {
            $xx .= ' - ';
        }
        $xx .= $this->getNombre();
        return $xx;
    }

    /**
     * @return String
     */
    public function getCodBarra(): ?string
    {
        return $this->codBarra;
    }

    /**
     * @param String $codBarra
     */
    public function setCodBarra(string $codBarra): void
    {
        $this->codBarra = $codBarra;
    }

    /**
     * @return String
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param String $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }


    public function getDetalle()
    {
        $xx = "";
        if ($this->nombre) {
            $xx = $this->nombre;
        }

        if ($this->descripcion) {
            $xx = $this->descripcion;
        }
        return $xx;
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
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * @param String $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return float
     */
    public function getPrecio(): float
    {
        return $this->precio;
    }

    /**
     * @param float $precio
     */
    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    /**
     * @return float
     */
    public function getFraccion(): float
    {
        return $this->fraccion;
    }

    /**
     * @param float $fraccion
     */
    public function setFraccion(float $fraccion): void
    {
        $this->fraccion = $fraccion;
    }

    /**
     * @return DocumentoTipo
     */
    public function getTipo(): ?ProductoTipo
    {
        return $this->tipo;
    }

    /**
     * @param DocumentoTipo $tipo
     */
    public function setTipo(ProductoTipo $tipo): void
    {
        $this->tipo = $tipo;
    }


    public function getTipoStock(): ?bool
    {
        return $this->tipoStock;
    }

    public function setTipoStock(bool $tipoStock): self
    {
        $this->tipoStock = $tipoStock;

        return $this;
    }

    /**
     * Indica la cantidad del primer dep
     */
    public function stock1(): int
    {
        $int = 0;
        foreach ($this->stock as $xx) {
            if ($xx->getDeposito()->getId() == 1) {
                $int = $xx->getCantidad();
            }
        }
        return $int;
    }

    /**
     * @ORM\prePersist
     */
    function prePersist()
    {
        if (empty($this->descripcion)) {
            $this->descripcion = $this->nombre;
        }
        return parent::prePersist();
    }

    /**
     * Devuelve el stock total de todos los depositos
     */
    public function stockTotal()
    {
        $i = 0;
        foreach ($this->stock as $stock) {
            if ($stock->getCantidad() > 0) {
                $i += $stock->getCantidad();
            }
        }
        return $i;
    }

    /**
     * Genera la cantidad de consumo desde una fecha
     * @param datetime $desde
     * @param datetime $hasta ,
     * @return float
     *
     */
    public function getConsumo(\DateTime $desde, \DateTime $hasta = null): float
    {
        $i = 0;
        foreach ($this->movimientos as $item) {
            if ($item->createAt >= $desde && (!$hasta || $item->createAt <= $hasta)) {
                $i += $item->getCantidad();
            }
        }
        return $i;
    }

    /**
     * Devuelve el stock en esa fecha
     * @param datetime $fecha
     * @return float|int
     */
    public function getStockFecha(datetime $fecha)
    {
        return $this->stockTotal() - $this->getConsumo($fecha);
    }
}
