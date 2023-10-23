<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepositoRepository")
 */
class Deposito extends Base
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;
    /**
     * @var string|null
     * @ORM\Column(type="string", length=50,nullable=true)
     */

    private $direccion;
    /**
     * @var string|null
     * @ORM\Column(type="string", length=20,nullable=true)
     */
    private $telefono;



    /**
     * @var stock[]
     * @ORM\OneToMany(targetEntity="App\Entity\Stock", mappedBy="deposito")
     */
    private $stock;

    /**
     * @ORM\Column(type="integer")
     */
    private $orden;

    /**
     * @ORM\OneToMany(targetEntity=Documento::class, mappedBy="deposito")
     */
    private $documentos;

    public function __construct()
    {
        parent::__construct();
        $this->documentos = new ArrayCollection();
    }




    /**
     * @ORM\PrePersist
     * @return \Doctrine\DBAL\Types\BooleanType;
     */
    public function prePersist()
    {
        //@ORM\HasLifecycleCallbacks()
        $this->createdAt = new \DateTime();
        //  $this->deleteAt= null;

        $user = Usuario::Actual();
        $this->createdBy = $user;
        $this->updateAt = $this->createdAt;
        $this->updatedBy = $this->createdBy;
    }

    /**
     * @ORM\PreUpdate
     * @return \Doctrine\DBAL\Types\BooleanType;
     */
    public function preUpdate()
    {
        $this->updateAt = new \DateTime();
        $user = Usuario::Actual();
        $this->updatedBy = $user;
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
     * @return Deposito
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     * @return Deposito
     */
    public function setNombre(string $nombre): Deposito
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    /**
     * @param null|string $direccion
     * @return Deposito
     */
    public function setDireccion(?string $direccion): Deposito
    {
        $this->direccion = $direccion;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    /**
     * @param null|string $telefono
     * @return Deposito
     */
    public function setTelefono(?string $telefono): Deposito
    {
        $this->telefono = $telefono;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Deposito
     */
    public function setCreatedAt( \DateTime $createdAt): Deposito
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Usuario
     */
    public function getCreatedBy(): Usuario
    {
        return $this->createdBy;
    }

    /**
     * @param Usuario $createdBy
     * @return Deposito
     */
    public function setCreatedBy(Usuario $createdBy): Deposito
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateAt(): \DateTime
    {
        return $this->updateAt;
    }

    /**
     * @param \DateTime $updateAt
     * @return Deposito
     */
    public function setUpdateAt( \DateTime $updateAt): Deposito
    {
        $this->updateAt = $updateAt;
        return $this;
    }

    /**
     * @return Usuario
     */
    public function getUpdatedBy(): Usuario
    {
        return $this->updatedBy;
    }

    /**
     * @param Usuario $updatedBy
     * @return Deposito
     */
    public function setUpdatedBy(Usuario $updatedBy): Deposito
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDeleteAt(): \DateTime
    {
        return $this->deleteAt;
    }

    /**
     * @param \DateTime $deleteAt
     * @return Deposito
     */
    public function setDeleteAt( \DateTime $deleteAt): Deposito
    {
        $this->deleteAt = $deleteAt;
        return $this;
    }

    /**
     * @return Usuario
     */
    public function getDeleteBy(): Usuario
    {
        return $this->deleteBy;
    }

    /**
     * @param Usuario $deleteBy
     * @return Deposito
     */
    public function setDeleteBy(Usuario $deleteBy): Deposito
    {
        $this->deleteBy = $deleteBy;
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

    public function  __toString()
    {
        return $this->getNombre();
    }

    /**
     * @return Collection|Documento[]
     */
    public function getDocumentos(): Collection
    {
        return $this->documentos;
    }

    public function addDocumento(Documento $documento): self
    {
        if (!$this->documentos->contains($documento)) {
            $this->documentos[] = $documento;
            $documento->setDeposito($this);
        }

        return $this;
    }

    public function removeDocumento(Documento $documento): self
    {
        if ($this->documentos->removeElement($documento)) {
            // set the owning side to null (unless already changed)
            if ($documento->getDeposito() === $this) {
                $documento->setDeposito(null);
            }
        }

        return $this;
    }

}
