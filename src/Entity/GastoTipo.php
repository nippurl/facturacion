<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GastoTipoRepository")
 */
class GastoTipo extends Base
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gasto", mappedBy="gastoTipo")
     */
    private $gastos;

    public function __construct()
    {
        $this->gastos = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    /**
     * @return Collection|Gasto[]
     */
    public function getGastos(): Collection
    {
        return $this->gastos;
    }

    public function addGasto(Gasto $gasto): self
    {
        if (!$this->gastos->contains($gasto)) {
            $this->gastos[] = $gasto;
            $gasto->setGastoTipo($this);
        }

        return $this;
    }

    public function removeGasto(Gasto $gasto): self
    {
        if ($this->gastos->contains($gasto)) {
            $this->gastos->removeElement($gasto);
            // set the owning side to null (unless already changed)
            if ($gasto->getGastoTipo() === $this) {
                $gasto->setGastoTipo(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}
