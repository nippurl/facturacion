<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentoTipoRepository")
 */
class DocumentoTipo extends Base
{

    const PATH = __DIR__ . '/../../public/img';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=10 )
     */
    private $letra;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     * Si es null es porque el numero lo debe poner el usuario
     */
    private $ultimo;

    /**
     * @var integer;
     * @ORM\Column(type="float")
     */
    private $caja;
    /**
     * @ORM\Column(type="float")
     * @var int
     */
    private $stock;


    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $menu;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * SI ese tipo de documento maneja pagos, aunque sea de mentira como el presupuesto
     * @var  int
     * @ORM\Column(type="smallint", options={"default"="1"})
     *
     */
    private $pagos;

    /**
     * @ORM\Column(type="integer", options={"default"="1"})
     * @var int
     */
    private $blanco;

    /**
     * Aceso directo si lleva
     * @ORM\Column(type="integer", nullable=true)
     */
    private $AD_orden;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $menu_imagen;

    /**
     * Indicasi le sistema debe generar un voucher por la compra
     * si es 1 genera un voucher automaticamente
     * @ORM\Column (type="integer")
     * @var int
     */
    private $voucher;

    /**
     * @return mixed
     */
    public function getDeposito()
    {
        return $this->deposito;
    }

    /**
     * @param mixed $deposito
     */
    public function setDeposito($deposito): void
    {
        $this->deposito = $deposito;
    }

    /**
     * @ORM\ManyToOne(targetEntity=Deposito::class, inversedBy="documentos")
     */
    private $deposito;


    /**
     * DocumentoTipo constructor.
     */


    public function __construct()
    {
        parent::__construct();
        $this->ultimo = 0;
        $this->caja = 0;
        $this->letra = "";
        $this->stock = 0;
        $this->blanco = 0;
        $this->menu = 0;
        $this->voucher = 0;

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
     * @return null|string
     */
    public function getLetra(): ?string
    {
        return $this->letra;
    }

    /**
     * @param null|string $letra
     */
    public function setLetra(?string $letra): void
    {
        $this->letra = $letra;
    }

    /**
     * @return int
     */
    public function getUltimo(): ?int
    {
        return $this->ultimo;
    }

    /**
     * @param int $ultimo
     */
    public function setUltimo(int $ultimo): void
    {
        $this->ultimo = $ultimo;
    }

    /**
     * @return int
     */
    public function getCaja(): int
    {
        return $this->caja;
    }

    /**
     * @param int $caja
     */
    public function setCaja(int $caja): void
    {
        $this->caja = $caja;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return int
     */
    public function getBlanco(): int
    {
        return $this->blanco;
    }

    /**
     * @param int $blanco
     */
    public function setBlanco(int $blanco): void
    {
        $this->blanco = $blanco;
    }

    /**
     * @return int
     */
    public function getMenu(): int
    {
        return $this->menu;
    }

    /**
     * @param int $menu
     */
    public function setMenu(int $menu): void
    {
        $this->menu = $menu;
    }

    /**
     * @return string
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function sacarNumero(): ?int
    {
        if (!is_null($this->getUltimo())) {
            $x = $this->getUltimo();
            $this->setUltimo($x + 1);
            return $x;
        } else {
            return null;
        }
    }


    public function __toString()
    {
        return $this->getNombre() ?? '';
    }

    /**
     * @return int
     */
    public function getPagos()
    {
        return $this->pagos;
    }

    /**
     * @param int $pagos
     */
    public function setPagos($pagos)
    {
        $this->pagos = $pagos;
    }

    public function getADOrden(): ?int
    {
        return $this->AD_orden;
    }

    public function setADOrden(?int $menu_orden): self
    {
        $this->AD_orden = $menu_orden;

        return $this;
    }

    public function getMenuImagen(): ?string
    {
        return $this->menu_imagen;
    }

    public function setMenuImagen(?string $menu_imagen): self
    {
        $this->menu_imagen = $menu_imagen;

        return $this;
    }

    public function getMenuImagenFile()
    {
        //Set path for easyadmin
        if ($this->menu_imagen) {

            return realpath(self::PATH . '/../' . $this->menu_imagen);
        } else {
            return null;
        }
    }

    public function setMenuImagenFile(UploadedFile $filename)
    {


        $name = $filename->getClientOriginalName() . '-' . uniqid() . '.' . $filename->getClientOriginalExtension();
        $name = basename($name);
        $filename->move(self::PATH, $name);
        //Only keep last part of filepath
        $this->setMenuImagen($name);
    }

    /**
     * @return int
     */
    public function getVoucher(): int
    {
        return $this->voucher;
    }

    /**
     * @param int $voucher
     */
    public function setVoucher(int $voucher): void
    {
        $this->voucher = $voucher;
    }

}
