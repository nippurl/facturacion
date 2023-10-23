<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PagoFormaTipoRepository")
 */
class PagoFormaTipo extends Base
{
    /**
     * PASA EL ID DEL EFECTIVO
     */
    public const EFECTIVO = 1;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="PagoForma", mappedBy="pagoFormaTipo", cascade={"all", "persist"})
     * @var PagoForma[] | ArrayCollection
     */
    private $cuotas;

    /**
     * @ORM\Column(type="smallint", options={"default"="1"})
     */
    private $sumatoria;

//

    /**
     * @ORM\Column (type="integer")
     * @var integer;
     */
    private $deuda;

    public function __construct()
    {
        parent::__construct();
        $this->cuotas = new ArrayCollection();
        $cuota = new PagoForma();
        $cuota->setPagoForma($this);
        $cuota->setCuotas(1);
        $cuota->setInteres(1);
        $this->cuotas->add($cuota);
        $this->sumatoria = 1;
        $this->deuda = 0;

    }

    /**
     * @return int
     */
    public function getDeuda(): int
    {
        return $this->deuda;
    }

    /**
     * @param int $deuda
     */
    public function setDeuda(int $deuda): void
    {
        $this->deuda = $deuda;
    }

    public function __toString()
    {
        return $this->getNombre();
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
    public function setId($id)
    {
        $this->id = $id;

    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }


    /**
     * @return PagoForma[]|ArrayCollection
     */
    public function getCuotas()
    {

        return $this->cuotas;
    }

    /**
     * @param PagoForma[]|ArrayCollection $cuotas
     */
    public function setCuotas($cuotas)

    {
        foreach ($cuotas as $cuota) {
            $cuota->setPagoForma($this);
        }
        $this->cuotas = $cuotas;
        return $this;
    }

    /**
     * @param PagoForma $cuota
     */
    public function addCuota(PagoForma $cuota)
    {
        //     $id  = ($cuota->getPagoForma()) ?   $cuota->getPagoForma()->getId(): null;
        //     if ($cuota->getPagoForma() && $id != $this->getId() ) {
        $cuota->setPagoForma($this);
        $this->cuotas->add($cuota);

        //     }
        // uncomment if you want to update other side
        return $this;
    }

    /**
     * @param PagoForma $cuota
     */
    public function removeCuota(PagoForma $cuota)
    {
        $cuota->setPagoForma(null);
        $this->cuotas->removeElement($cuota);
        // uncomment if you want to update other side
        return $this;

    }


    public function CuotasStr()
    {
        $xx = "";
        foreach ($this->cuotas as $cc) {
            $xx .= ($xx) ? ' - ' : '';
            $xx .= $cc->getCuotas();
        }
        return $xx;
    }

    public function getSumatoria(): ?int
    {
        return $this->sumatoria;
    }

    public function setSumatoria(int $sumatoria): self
    {
        $this->sumatoria = $sumatoria;

        return $this;
    }

    // add your own fields
}
