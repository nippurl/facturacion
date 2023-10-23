<?php

namespace App\Entity;

use App\Repository\OrdenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdenRepository::class)
 * @ORM\Table(name="agenda_orden")
 */
class Orden extends Base
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Usuario::class, inversedBy="orden", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @ORM\OrderBy({"nombre","ASC"})
     */
    private $responsable;

    /**
     * @ORM\Column(type="integer")
     */
    private $orden;

    /**
     * @ORM\ManyToOne(targetEntity=AgendaArea::class, inversedBy="orden")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agendaArea;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResponsable(): ?Usuario
    {
        return $this->responsable;
    }

    public function setResponsable(Usuario $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }

    public function getAgendaArea(): ?AgendaArea
    {
        return $this->agendaArea;
    }

    public function setAgendaArea(?AgendaArea $agendaArea): self
    {
        $this->agendaArea = $agendaArea;

        return $this;
    }
    public function __toString()
    {
        return $this->orden.'-'.$this->responsable;
    }
}
