<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 * @ORM\Table(name="usuario" )
 * @ORM\HasLifecycleCallbacks()
 */
class Usuario extends Base implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=50)
     */
    private $nick;


    /**
     * @var string
     * @ Assert\NotBlank()
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $password;
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string",length=20, nullable=true, unique=true)
     */

    private $dni;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;
    /**
     * @var Usuario
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     */
    private $createdBy;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;
    /**
     * @var Usuario
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     */
    private $updatedBy;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $direccion;
    /**
     * @var null| string
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $telefono;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $localidad;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UsuarioNivel", inversedBy="usuarios")
     * @var UsuarioNivel[]
     */
    private $nivel;

    /**
     * @var integer
     * @ORM\Column(type="integer", options={"default"="0"})
     */
    private $blanco;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="vendedor")
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comision", mappedBy="vendedor")
     * @var Comision[]
     */
    private $comisiones;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $visible;

    /**
     * @ORM\OneToOne(targetEntity=Orden::class, mappedBy="responsable", cascade={"persist", "remove"})
     */
    private $orden;

    /**
     * @return Comision[]
     */
    public function getComisiones()
    {
        return $this->comisiones;
    }



    /**
     * @return int
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param int $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }


    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        //parent::__preUpdate();
        $this->updatedAt= new \DateTime();
        $this->updatedBy= Usuario::Actual();
        return;
    }


    public function __construct()
    {
        $this->nivel = new ArrayCollection();
        $this->nombre = "";
        $this->nick = "";
        $this->password = "";
        $this->nivel = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->createdBy = Usuario::Actual();
        $this->updatedAt= new \DateTime();
        $this->updatedBy= Usuario::Actual();
        $this->items = new ArrayCollection();
        $this->blanco=1;
        $this->visible =1;


    }

    /**
     * @return Usuario
     */
    static public function Actual()
    {
        global $kernel;
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $container = $kernel->getContainer();
        /* @var $container  \Symfony\Component\DependencyInjection\Container */
        /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\tokenStorage $token */
        $token = $container->get('security.token_storage');
        if ($token->getToken()) {
            $user = $token->getToken()->getUser();

            // }else{
            //    $user = $token->get()
        }
        return $user;

    }

    /**
     * @ORM\PostUpdate()
     * @return bool
     */
    public function postUpdate()
    {
        $testigo = Testigo::RegistrarObjeto($this, 'Modifico');

        return true;
    }

    /**
     * @ORM\PostPersist()
     */
    public function postPersist()
    {
        $testigo = Testigo::RegistrarObjeto($this, 'Nuevo');

        return true;
    }

    /**
     * @return string
     */
    public function getNick(): string
    {
        return $this->nick;
    }

    /**
     * @param string $nick
     */
    public function setNick(string $nick): void
    {
        $this->nick = $nick;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(?string $password): void
    {
        if (!empty($password)) {
            $this->password = $password;
        }

    }



    public function __toString()
    {
        return $this->getNombre();
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return strval($this->getId());
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $this->id = intval($serialized);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $arr = [];
        foreach ($this->nivel as $item) {
            /** @var $item UsuarioNivel */
            $arr[] = $item->getCodigo();
        }
        return $arr;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TO DO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->nick;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Evento[]
     */
    public function getEventos()
    {
        $arr = [];
        foreach ($this->eventos as $responsable) {
            $arr[] = $responsable->getEvento();
        }
        return $arr;
    }

    public function getSubalternos()
    {
        $arr = $arr2 = [];

        foreach ($this->getNivel() as $nivel) {
            $arr = array_merge($arr, $nivel->getUsuariosInferiores());
        }
        foreach ($arr as $item) {
            if ($item) {
               $arr2[]=$item;
            }
        }
        return array_unique($arr2);
    }



    /**
     * @return mixed
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * @param mixed $dni
     */
    public function setDni($dni): void
    {
        $this->dni = $dni;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Usuario
     */
    public function getCreatedBy(): ?Usuario
    {
        return $this->createdBy;
    }

    /**
     * @param Usuario $createdBy
     */
    public function setCreatedBy(?Usuario $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Usuario
     */
    public function getUpdatedBy(): ?Usuario
    {
        return $this->updatedBy;
    }

    /**
     * @param Usuario $updatedBy
     */
    public function setUpdatedBy(?Usuario $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return string
     */
    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    /**
     * @param string $direccion
     */
    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
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
     */
    public function setTelefono(?string $telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return null|string
     */
    public function getLocalidad(): ?string
    {
        return $this->localidad;
    }

    /**
     * @param null|string $localidad
     */
    public function setLocalidad(?string $localidad): void
    {
        $this->localidad = $localidad;
    }

    /**
     * @return UsuarioNivel[]
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * @param UsuarioNivel[] $nivel
     */
    public function setNivel(array $nivel): void
    {
        $this->nivel = $nivel;
    }

    /**
     * @param UsuarioNivel $nivel
     */
    public function addNivel(UsuarioNivel $nivel)
    {
        $this->nivel[] = $nivel;
    }

    /**
     * @param UsuarioNivel $nivel
     */
    public function removeNivel(UsuarioNivel $nivel)
    {
        if (false !== $key = array_search($nivel, $this->nivel, true)) {
            array_splice($this->nivel, $key, 1);
        }
    }

    /**
     * @return int
     */
    public function getBlanco(): int
    {
        return $this->blanco;
    }

    /**
     * @param int $blanco
     */
    public function setBlanco(int $blanco): void
    {
        $this->blanco = $blanco;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setVendedor($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getVendedor() === $this) {
                $item->setVendedor(null);
            }
        }

        return $this;
    }

    public function getOrden(): ?Orden
    {
        return $this->orden;
    }

    public function setOrden(Orden $orden): self
    {
        // set the owning side of the relation if necessary
        if ($orden->getResponsable() !== $this) {
            $orden->setResponsable($this);
        }

        $this->orden = $orden;

        return $this;
    }


}
