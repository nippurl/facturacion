<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductoTipoRepository")
 */
class ProductoTipo extends Base
{
    /**
     * La cantidad de niveles maximo de nivles
     */
    const NIVELES =5;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var String
     * @ORM\Column(type="string", length=50)
     */

    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Producto", mappedBy="tipo")
     * @var $productos []
     */
    private $productos;

    /**
     * @ORM\ManyToOne(targetEntity=ProductoTipo::class, inversedBy="hijos")
     */
    private $padre;

    /**
     * @ORM\OneToMany(targetEntity=ProductoTipo::class, mappedBy="padre")
     */
    private $hijos;

    public function __construct()
    {
        parent::__construct();
        $this->hijos = new ArrayCollection();
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
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param String $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }


    public function __toString()
    {
        return $this->getNombre()??'';
    }

    public function getPadre(): ?self
    {
        return $this->padre;
    }

    public function setPadre(?self $padre): self
    {
        $this->padre = $padre;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getHijos(): Collection
    {
        return $this->hijos;
    }

    public function addHijo(self $hijo): self
    {
        if (!$this->hijos->contains($hijo)) {
            $this->hijos[] = $hijo;
            $hijo->setPadre($this);
        }

        return $this;
    }

    public function removeHijo(self $hijo): self
    {
        if ($this->hijos->removeElement($hijo)) {
            // set the owning side to null (unless already changed)
            if ($hijo->getPadre() === $this) {
                $hijo->setPadre(null);
            }
        }

        return $this;
    }

    /**
     * Devuelve una lista de los hijos recursiva
     * @return ProductoTipo[]
     */
    public function getHijosR($nivel = self::NIVELES)
    {
        $xx = [$this];
        if ($nivel > 0) {
            foreach ($this->getHijos() as $hijo) {
                $xx = array_merge($xx, $hijo->getHijosR($nivel - 1));
            }
        }

        return $xx;
    }

    /**
     * @param $nivel
     * @return ProductoTipo[]
     */
    public function getPadres ($nivel = self::NIVELES){
        $p = $this;

        while ($nivel && $p->getPadre()  && ($p != $p->getPadre())){
                $nivel = 0 ;
                $xx[] = $p->getPadre();
                $p = $p->getPadre();
                $nivel --;
        }

     return $xx ;
    }

}
