<?php

namespace App\Entity;

use App\Repository\AgendaAreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgendaAreaRepository::class)
 */
class AgendaArea extends Base
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
     * @ORM\OneToMany(targetEntity=Orden::class, mappedBy="agendaArea", orphanRemoval=true,cascade={"persist"})
     * @ORM\OrderBy({"orden"="ASC"})
     */
    private $usuarios;

    /**
     * @ORM\Column(type="integer")
     */
    private $orden;

    public function __construct()
    {
        parent::__construct();
        $this->usuarios= new ArrayCollection();
    }

    public function getId(): ?int
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
     * @return Collection|Orden[]
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(Orden $orden): self
    {
        if (!$this->usuarios->contains($orden)) {
            $this->usuarios[] = $orden;
            $orden->setAgendaArea($this);
        }

        return $this;
    }

    public function removeUsuario(Orden $orden): self
    {
        if ($this->usuarios->removeElement($orden)) {
            // set the owning side to null (unless already changed)
            if ($orden->getAgendaArea() === $this) {
                $orden->setAgendaArea(null);
            }
        }

        return $this;
    }

    public function setOrden(int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }

    public function getOrden (){
     return $this->orden;
    }

    public function __toString()
    {
     return $this->getNombre()??"";
    }
}
