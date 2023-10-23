<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactoRepository")
 */
class Contacto extends Base
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @var string
     */


    private $razon;
    /**
     * @ORM\Column(type="string", nullable=true, length=20)
     * @var string
     */

    private $cuil;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */

    private $direccion;
    /**
     * @ORM\Column(type="string", nullable=true, length=20)
     * @var string
     */

    private $telefono;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */

    private $observaciones;

    /**
     * @var Documento[]
     * @ORM\OneToMany(targetEntity="App\Entity\Documento", mappedBy="contacto")
     */
    private $docuemntos;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fecha_nac;

    /**
     * @ORM\OneToMany(targetEntity=Agenda::class, mappedBy="contacto")
     */
    private $agendas;

    /**
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * @return Usuario
     */
    public function getCreateBy()
    {
        return $this->createBy;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * @return Usuario
     */
    public function getUpdateBy()
    {
        return $this->updateBy;
    }

    /**
     * @return mixed
     */
    public function getFechaNac(): ?\DateTime
    {
        return $this->fecha_nac;
    }

    /**
     * @param mixed $fecha_nac
     */
    public function setFechaNac($fecha_nac)
    {
        $this->fecha_nac = $fecha_nac;
    }

    public function __construct()
    {
        $this->docuemntos = new ArrayCollection();
        $this->agendas = new ArrayCollection();
    }

    public static function ConsumidorFinal(): Contacto
    {
        global $kernel;
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $container = $kernel->getContainer();
        /* @var $container  \Symfony\Component\DependencyInjection\Container */
        $em = $container->get('doctrine.orm.default_entity_manager');

        return $em->find(Contacto::class, 0);
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
    public function getRazon()
    {
        return $this->razon;
    }

    /**
     * @param string $razon
     */
    public function setRazon($razon)
    {
        $this->razon = $razon;
    }

    /**
     * @return string
     */
    public function getCuil()
    {
        return $this->cuil;
    }

    /**
     * @param string $cuil
     */
    public function setCuil($cuil)
    {
        $this->cuil = $cuil;
    }

    /**
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param string $direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * @param string $observaciones
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    }

    /**
     * @return Documento[]DocCabType::getDate()
     */
    public function getDocuemntos()
    {
        return $this->docuemntos;
    }

    /**
     * @param Documento[] $docuemntos
     */
    public function setDocuemntos($docuemntos)
    {
        $this->docuemntos = $docuemntos;
    }

    /**
     * @param Documento $docuemnto
     */
    public function addDocuemnto(Documento $docuemnto)
    {
        $this->docuemntos[] = $docuemnto;
        $docuemnto->setContacto($this);
    }

    /**
     * @param Documento $docuemnto
     */
    public function removeDocuemnto(Documento $docuemnto)
    {
        if (false !== $key = array_search($docuemnto, $this->docuemntos, true)) {
            array_splice($this->docuemntos, $key, 1);
            $docuemnto->setContacto(null);
        }
    }

    public function __toString()
    {
        return $this->razon . " (" . $this->cuil . ")";
    }


    // add your own fields

    /**
     * @return Collection|Agenda[]
     */
    public function getAgendas(): Collection
    {
        return $this->agendas;
    }

    public function addAgenda(Agenda $agenda): self
    {
        if (!$this->agendas->contains($agenda)) {
            $this->agendas[] = $agenda;
            $agenda->setContacto($this);
        }

        return $this;
    }

    public function removeAgenda(Agenda $agenda): self
    {
        if ($this->agendas->removeElement($agenda)) {
            // set the owning side to null (unless already changed)
            if ($agenda->getContacto() === $this) {
                $agenda->setContacto(null);
            }
        }

        return $this;
    }

    public function getDeuda(): float
    {
        $d = 0;
        if ($this->getId() != 0) {
            foreach ($this->getDocuemntos() as $docuemnto) {
                foreach ($docuemnto->getPagos() as $pago) {
                    if ($pago->getForma()->getPagoForma()->getDeuda()) {
                        $d += $pago->getMonto();
                    }
                }

            }
        }
        return $d;
    }


}
