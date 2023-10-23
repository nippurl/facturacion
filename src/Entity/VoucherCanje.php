<?php

namespace App\Entity;

use App\Repository\VoucherCanjeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoucherCanjeRepository::class)
 */
class VoucherCanje extends Base
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(type="integer")
     */
    private $comanda;

    /**
     * @ORM\ManyToOne(targetEntity=Voucher::class, inversedBy="canjes")
     */
    private $voucher;

    public function __construct(Voucher  $voucher)
    {
        parent::__construct();
        $this->voucher = $voucher;
        $this->fecha = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getComanda(): ?int
    {
        return $this->comanda;
    }

    public function setComanda(int $comanda): self
    {
        $this->comanda = $comanda;

        return $this;
    }

    public function getVoucher(): ?Voucher
    {
        return $this->voucher;
    }

    public function setVoucher(?Voucher $voucher): self
    {
        $this->voucher = $voucher;

        return $this;
    }
}
