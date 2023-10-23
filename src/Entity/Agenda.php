<?php

namespace App\Entity;

use App\Repository\AgendaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgendaRepository::class)
 * @ORM\Table(
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="unico",
 *            columns={"fecha", "hora","usuario_id"})
 *    }
 * )
 */
class Agenda extends Base
{
    /**
     * La duracion minima entre turnos
     */
    const DURACION = 30;
    /**
     *
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
     * @ORM\Column(type="time")
     */
    private $hora;

    /**
     * @ORM\ManyToOne(targetEntity=Contacto::class, inversedBy="agendas")
     * @ORM\JoinColumn(nullable=true)
     */
    private $contacto;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToMany(targetEntity=Producto::class)
     */
    private $productos;

    /**
     * @ORM\Column(type="integer")
     */
    private $duracion;

    public static function DuracionArray()
    {
        $xx = [];
        $sum = Agenda::DURACION;
        for ($i = 1; $i <= 6; $i++) {
            $hora = intdiv($sum, 60);
            $min = $sum % 60;
            $txt = str_pad($hora, 2, "0", STR_PAD_LEFT);
            $txt .= ":" . str_pad($min, 2, "0", STR_PAD_LEFT);
            $xx[$txt] = $sum;
            $sum += Agenda::DURACION;
        }
        return $xx;
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

    public function getHora(): ?\DateTimeInterface
    {
        return $this->hora;
    }

    public function setHora(\DateTimeInterface $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    public function getContacto(): ?Contacto
    {
        return $this->contacto;
    }

    public function setContacto(?Contacto $contacto): self
    {
        $this->contacto = $contacto;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function __construct(\DateTime $fecha = null)
    {
        parent::__construct();
        $this->fecha = $fecha ?? new \DateTime('today');
        $this->usuario = Usuario::Actual();
        $this->productos = new ArrayCollection();
        $this->duracion = self::DURACION;
    }

    /**
     * @return Collection|Producto[]
     */
    public function getProductos(): Collection
    {
        return $this->productos;
    }

    public function addProducto(Producto $producto): self
    {
        if (!$this->productos->contains($producto)) {
            $this->productos[] = $producto;
        }

        return $this;
    }

    public function removeProducto(Producto $producto): self
    {
        $this->productos->removeElement($producto);

        return $this;
    }

    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    public function setDuracion(int $duracion): self
    {
        $this->duracion = $duracion;

        return $this;
    }

    /**
     * Calcula la hora de final de la agenda
     * @return \DateTime
     * @throws \Exception
     */
    public function getHoraFin (): \DateTime{
        $time =clone $this->getHora();
        $time->add(new \DateInterval('PT' . $this->duracion . 'M'));
        return $time;
    }

    /**
     * Sabiendo la duracion y el tiempo de salto calcula la cantidad de intervalos de salto
     * @return int
     */
    public function getDuracionIntervalo ():int {
        $i =1;
        if ($this->duracion) {
            $i = intdiv( $this->duracion, self::DURACION);
        }
     return $i;
    }

}
