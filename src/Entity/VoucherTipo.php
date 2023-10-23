<?php

namespace App\Entity;

use App\Repository\VoucherTipoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoucherTipoRepository::class)
 */
class VoucherTipo extends Base
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer")
     */
    private $cant;

    /**
     * @ORM\OneToMany(targetEntity=Voucher::class, mappedBy="voucherTipo")
     */
    private $vouchers;

    /**
     * VoucherTipo constructor.
     */

    public function __construct()
    {
        parent::__construct();
        $this->vouchers = new ArrayCollection();
        $this->cant =1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCant(): ?int
    {
        return $this->cant;
    }

    public function setCant(int $cant): self
    {
        $this->cant = $cant;

        return $this;
    }

    /**
     * @return Collection|Voucher[]
     */
    public function getVouchers(): Collection
    {
        return $this->vouchers;
    }

    public function addVoucher(Voucher $voucher): self
    {
        if (!$this->vouchers->contains($voucher)) {
            $this->vouchers[] = $voucher;
            $voucher->setVoucherTipo($this);
        }

        return $this;
    }

    public function removeVoucher(Voucher $voucher): self
    {
        if ($this->vouchers->removeElement($voucher)) {
            // set the owning side to null (unless already changed)
            if ($voucher->getVoucherTipo() === $this) {
                $voucher->setVoucherTipo(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNombre() ?? '';
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

}
