<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GastoRepository")
 */
class Gasto extends Base
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * Solo acepta fecha a futuro
     * @Assert\GreaterThanOrEqual("today", message="NO PUEDE SE PUEDE CARGAR GASTOS PASADOS")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="float")
     */
    private $importe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GastoTipo", inversedBy="gastos")
     */
    private $gastoTipo;

    public function __construct()
    {
        parent::__construct();
        $this->fecha= new \DateTime('today');
        $this->importe = 0;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
      /* Resuelto con agregar a futuro
      $base = $this->fecha->format('Y-m-d');
        $nueva = $fecha->format('Y-m-d');
        $hoy = (new \DateTime())->format('Y-m-d');
        if ($base >  $nueva|| $ ) {

        }*/
        $this->fecha = $fecha;

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

    public function getImporte(): ?float
    {
        return $this->importe;
    }

    public function setImporte(float $importe): self
    {
        $this->importe = $importe;

        return $this;
    }

    public function getGastoTipo(): ?GastoTipo
    {
        return $this->gastoTipo;
    }

    public function setGastoTipo(?GastoTipo $gastoTipo): self
    {
        $this->gastoTipo = $gastoTipo;

        return $this;
    }
    public function getCreateAt (): \DateTime{
     return $this->createAt;
    }
}
