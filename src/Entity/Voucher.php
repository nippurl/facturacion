<?php

namespace App\Entity;

use App\Repository\VoucherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoucherRepository::class)
 */
class Voucher extends Base
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $numero;


    /**
     * @ORM\Column(type="date")
     */
    private $compra_fecha;

    /**
     * @ORM\Column(type="integer")
     */
    private $compra_numero;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $canje_fecha;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $canje_numero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=VoucherTipo::class, inversedBy="vouchers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $voucherTipo;

    /**
     * @ORM\OneToMany(targetEntity=VoucherCanje::class, mappedBy="voucher")
     */
    private $canjes;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $impresoAt;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     */
    private $impresoBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getFechaCompra(): ?\DateTimeInterface
    {
        return $this->fecha_compra;
    }

    public function setFechaCompra(\DateTimeInterface $fecha_compra): self
    {
        $this->fecha_compra = $fecha_compra;

        return $this;
    }

    public function getCompraFecha(): \DateTimeInterface
    {
        return $this->compra_fecha;
    }

    public function setCompraFecha(\DateTimeInterface $compra_fecha): self
    {
        $this->compra_fecha = $compra_fecha;

        return $this;
    }

    public function getCompraNumero(): ?int
    {
        return $this->compra_numero;
    }

    public function setCompraNumero(int $compra_numero): self
    {
        $this->compra_numero = $compra_numero;

        return $this;
    }

    public function getCanjeFecha(): ?\DateTimeInterface
    {
        return $this->canje_fecha;
    }

    public function setCanjeFecha(?\DateTimeInterface $canje_fecha): self
    {
        $this->canje_fecha = $canje_fecha;

        return $this;
    }

    public function getCanjeNumero(): ?int
    {
        return $this->canje_numero;
    }

    public function setCanjeNumero(?int $canje_numero): self
    {
        $this->canje_numero = $canje_numero;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }
    public function __construct()
    {
        $this->compra_fecha=new \DateTime();
        $this->canjes = new ArrayCollection();
    }

    public function getVoucherTipo(): ?VoucherTipo
    {
        return $this->voucherTipo;
    }

    public function setVoucherTipo(?VoucherTipo $voucherTipo): self
    {
        $this->voucherTipo = $voucherTipo;

        return $this;
    }

    /**
     * @return Collection|VoucherCanje[]
     */
    public function getCanjes(): Collection
    {
        return $this->canjes;
    }

    public function addCanje(VoucherCanje $canje): self
    {
        if (!$this->canjes->contains($canje)) {
            $this->canjes[] = $canje;
            $canje->setVoucher($this);
        }

        return $this;
    }

    public function removeCanje(VoucherCanje $canje): self
    {
        if ($this->canjes->removeElement($canje)) {
            // set the owning side to null (unless already changed)
            if ($canje->getVoucher() === $this) {
                $canje->setVoucher(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->voucherTipo . " N ". $this->numero;
    }

    public function getImpresoAt(): ?\DateTimeInterface
    {
        return $this->impresoAt;
    }

    public function setImpresoAt(?\DateTimeInterface $impresoAt): self
    {
        $this->impresoAt = $impresoAt;

        return $this;
    }

    public function getImpresoBy(): ?Usuario
    {
        return $this->impresoBy;
    }

    public function setImpresoBy(?Usuario $impresoBy): self
    {
        $this->impresoBy = $impresoBy;

        return $this;
    }

    /**
     * Lo pone como ya impreso
     */
    public function impreso()
    {
        $this->impresoAt = new \DateTime();
        $this->impresoBy = Usuario::Actual();
    }
}
