<?php

namespace App\Entity;

use App\Repository\MovimientoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
#use DocumentoTipo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentoRepository")
 * @ORM\Table(indexes={
 *     @ORM\Index(name="fecha", columns={"fecha"})
 * })
 */
class Documento extends Base
{
    const Cancelado = -1;
    const Cerrado =1;
    /** @var int Es Cuando no tiene tipo el docuemtno o es inicial */
    const SINTIPO = null;
    /** @var int Id de la caja in inical */
    const CAJA_INICIAL = 0;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     *
     * @var DocumentoTipo;
     * @ORM\ManyToOne(targetEntity="App\Entity\DocumentoTipo")
     */
    private $tipo;
    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numero;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $total;


    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     *
     */
    private $createdAt;

    /**
     * @var Usuario
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     */
    private $createdBy;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     *
     */
    private $updatedAt;


    /**
     * @var \App\Entity\Usuario
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     */
    private $updatedBy;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     *
     */
    private $deleteAt;


    /**
     * @var \App\Entity\Usuario
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     */
    private $deleteBy;

    /**
     * @var Movimiento[]
     * @ORM\OneToMany(targetEntity="App\Entity\Movimiento", mappedBy="documento")
     */
    private $movimientos;
    /**
     * @var int
     * -1 es cancelado
     * 0 es prueba
     * 1 es cerrado
     * @ORM\Column(type="integer", options={"default"="0"})
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contacto", inversedBy="docuemntos")
     * @var Contacto
     */
    private $contacto;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="documento")
     * @var Item[]
     */
    private $items;
    /**
     * @var ArrayCollection| Pago[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Pago", mappedBy="documento")
     */

    private $pagos;

    /**
     * @ORM\ManyToOne(targetEntity=Deposito::class, inversedBy="documentos")
     */
    private $deposito;



    public function __construct(DocumentoTipo $tipo)
    {
        parent::__construct();
        $this->fecha = new \DateTime('today');
        $this->total = 0;
        if ($tipo) {
            $this->tipo = $tipo;
            $this->numero = $tipo->getUltimo();
            $this->deposito = $tipo->getDeposito();
        }
        $this->createdBy = Usuario::Actual();
        $this->createdAt = new \DateTime();
        $this->updatedBy = $this->createdBy;
        $this->updatedAt = $this->createdAt;
        $this->estado = 0;
        $this->items = new ArrayCollection();
        $this->contacto = Contacto::ConsumidorFinal();
        $this->pagos = new ArrayCollection();


    }

    /**
     * @ORM\PrePersist
     * @return \Doctrine\DBAL\Types\BooleanType;
     */
    public function prePersist()
    {
        //@ORM\HasLifecycleCallbacks()
        $this->createdAt = new \DateTime();
        //  $this->deleteAt= null;

        $this->createdBy = Usuario::Actual();
        $this->updateAt = $this->createdAt;
        $this->updatedBy = $this->createdBy;
    }

    /**
     * @ORM\PreUpdate
     * @return \Doctrine\DBAL\Types\BooleanType;
     */
    public function preUpdate()
    {

        $user = Usuario::Actual();
        $this->updatedBy = $user;
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
     * @return \DateTime
     */
    public function getFecha(): \DateTime
    {
        return $this->fecha;
    }

    /**
     * @param \DateTime $fecha
     */
    public function setFecha(\DateTime $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return datetime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param datetime $createdAt
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
     * @return datetime
     */
    public function getDeleteAt(): \DateTime
    {
        return $this->deleteAt;
    }

    /**
     * @param datetime $deleteAt
     */
    public function setDeleteAt(\DateTime $deleteAt): void
    {
        $this->deleteAt = $deleteAt;
    }

    /**
     * @return Usuario
     */
    public function getDeleteBy(): Usuario
    {
        return $this->deleteBy;
    }

    /**
     * @param Usuario $deleteBy
     */
    public function setDeleteBy(Usuario $deleteBy): void
    {
        $this->deleteBy = $deleteBy;
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
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param int $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return Contacto
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * @param Contacto $contacto
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;
    }

    /**
     * @param Movimiento $movimiento
     */
    public function addMovimiento(Movimiento $movimiento)
    {
        $movimiento->setDocumento($this);
        $this->movimientos[] = $movimiento;
    }

    /**
     * @param Movimiento $movimiento
     */
    public function removeMovimiento(Movimiento $movimiento)
    {
        $movimiento->setDocumento(null);
        if (false !== $key = array_search($movimiento, $this->movimientos, true)) {
            array_splice($this->movimientos, $key, 1);
        }
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $item->setDocumento($this);
        $this->items[] = $item;
    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $item->setDocumento(null);
        if (false !== $key = array_search($item, $this->items, true)) {
            array_splice($this->items, $key, 1);
        }
    }

    public function __toString()
    {
        return $this->getTipo() . "-" . $this->getNumero();
    }

    /**
     * @return DocumentoTipo
     */
    public function getTipo(): DocumentoTipo
    {
        return $this->tipo;
    }

    /**
     * @param DocumentoTipo $tipo
     */
    public function setTipo(DocumentoTipo $tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return int
     */
    public function getNumero(): ?int
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     */
    public function setNumero(int $numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return Pago[]|ArrayCollection
     */
    public function getPagos()
    {
        return $this->pagos;
    }

    /**
     * @param Pago[]|ArrayCollection $pagos
     */
    public function setPagos($pagos)
    {
        $this->pagos = $pagos;
    }

    /**
     * @param Pago $pago
     */
    public function addPago(Pago $pago)
    {
        $this->pagos->add($pago);
        // uncomment if you want to update other side
        $pago->setDocumento($this);
    }

    /**
     * @param Pago $pago
     */
    public function removePago(Pago $pago)
    {
        $this->pagos->removeElement($pago);
        // uncomment if you want to update other side
        $pago->setDocumento(null);
    }

    /**
     * Define cuanto falta pagar
     */
    public function getSaldo(): float
    {
        $xx = $this->getTotal() - $this->getPagado();
        return round($xx, 2);
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        if ($this->total == 0) {
            $this->total = $this->getItemsTotal();
        }
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    private function getItemsTotal(): float
    {
        $xx = 0;
        foreach ($this->getItems() as $item) {
            $xx += $item->getTotal();
        }
        return $xx;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * Devuleve el formulario contabilizado como pagado la deuda
     * @return float
     */
    public function getPagado(): float
    {
        $xx = 0;
        foreach ($this->pagos as $pago) {
            $xx += $pago->getMonto();
        }

        return $xx;
    }
    /**
     * Devuleve el formulario contabilizado como sin contar la deuda
     * @return float
     */
    public function getPagadoSinDeuda(): float
    {
        $xx = 0;
        foreach ($this->pagos as $pago) {

            if ($pago->getForma()->getPagoForma()->getDeuda() ==0) {
                $xx += $pago->getMonto();
            }

        }

        return $xx;
    }


    /**
     * Es cuando se cierra la edicion y se genera  el movimiento
     */
    public function concretar(EntityManager $entityManager)
    {
        // si la comanda no tiene cliente, agregar consumidor final
        if (!$this->contacto) {
            $cf = Contacto::ConsumidorFinal();
            $this->setContacto($cf);
        }

        // Reservar Numero en tipo docuemtno
        $this->estado=1;
        $this->registrarNumero();
/// Si el documento es de tipo voucher debe genrrar al toque el voucher,
        if ($this->getTipo()->getVoucher()) {

        }

        $entityManager->persist($this->getTipo());

        //TODO hacer los descuentos de productos en depositos y hacere movimientos

        $this->descontarStock($entityManager);


        // TODO caja
    }

    private function registrarNumero()
    {
        $xx =$this->getTipo()->sacarNumero();
        if ($xx) {
            $this->numero = $xx;
        }

    }

    /**
     * Me devuelve el ultimo item
     * @return Item|bool
     */
    public function ultItem()
    {
        return $this->items->last();
    }

    /**
     *  Genera el Descuento de Stock con sus movimientos
     */
    private function descontarStock(EntityManager $entityManager)
    {

        $stock = $this->getTipo()->getStock();
        if ($stock) {
            /** @var Item $item */
            /** @var MovimientoRepository $MR */
            $MR = $entityManager->getRepository(Movimiento::class);
            foreach ($this->items as $item) {
                if ($item->getProducto()->getTipoStock()) {
                    $item->setMovimientos($MR->moverStock($item));
                }
            $entityManager->persist($item);
            }
            $entityManager->flush();
        }
    }

    public function borrar()
    {
        $this->deleteAt = new \DateTime();
        $this->deleteBy = Usuario::Actual();
    }

public function getProductos ():array{
        $arr = [];
    foreach ($this->getItems() as $item) {
        if ($item->getProducto()) {
            $arr[] = $item->getProducto()->getNombre() . "(" . $item->getTotal() . ")";
        } else {
            $arr[] = $item->getDescripcion() . "(" . $item->getTotal() . ")";
        }

    }
 return $arr;
}

    /**
     * busca la deuda reconocida y la parte no pagada
     * @return float
     */
    public function getDeuda()
    {
        $x = $this->getTotal();
        foreach ($this->getPagos() as $pago)
            if ($pago->getForma()->getPagoForma()->getDeuda() ==0 ) {
                $x -= $pago->getMontoTotal();
        }

        return $x;
}

    public function getDeposito(): ?Deposito
    {
        return $this->deposito;
    }

    public function setDeposito(?Deposito $deposito): self
    {
        $this->deposito = $deposito;

        return $this;
    }

    public function cancelar()
    {
        $this->estado = self::Cancelado;
    }

}
