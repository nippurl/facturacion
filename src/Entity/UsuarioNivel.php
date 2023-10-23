<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioNivelRepository")
 */
class UsuarioNivel extends Base
{
    public const COBRADOR=3;
    public const VENDEDOR=5;


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30)
     */
    private $codigo;



    /**
     * @var null|UsuarioNivel
     * @ORM\ManyToOne(targetEntity="App\Entity\UsuarioNivel", inversedBy="inferiores")
     */
    private $supervisor;

    /**
     * @var UsuarioNivel[]
     * @ORM\OneToMany(targetEntity="App\Entity\UsuarioNivel", mappedBy="supervisor")
     *
     */


    private $inferiores;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Usuario", mappedBy="nivel")
     * @var Usuario[]
     */
    private $usuarios;


    /**
     * @var int
     * @ORM\Column(type="integer", options={"default":1})
     */

    private $negro;



    function __construct()
    {
        $this->usuarios = new ArrayCollection();
        $this->inferiores = new ArrayCollection();
        $this->negro=1;

        return;
    }


    /**
     * @return UsuarioNivel|null
     */
    public function getSupervisor(): ?UsuarioNivel
    {
        return $this->supervisor;
    }

    /**
     * @param UsuarioNivel|null $supervisor
     */
    public function setSupervisor(?UsuarioNivel $supervisor): void
    {
        $this->supervisor = $supervisor;
    }

    /**
     * @return UsuarioNivel[]
     */
    public function getInferiores()
    {
        $arr = [];
        $arr[] = $this;
        foreach ($this->inferiores as $inferior) {
            $arr = array_merge($arr , $inferior->getInferiores());
        }
        return array_unique($arr);
    }

    public function getUsuariosInferiores ()
    {
        $niveles = $this->getInferiores();
        $arr = [];
        foreach ($niveles as $nivel) {
            $arr = array_merge($arr, $nivel->getUsuarios());
        }
     return array_unique($arr);
    }

    /**

*/

    public function __toString (){
     return $this->nombre;
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
     * @return string
     */
    public function getNombre(): ?string
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
     * @return string
     */
    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    /**
     * @param string $codigo
     */
    public function setCodigo(string $codigo): void
    {
        $this->codigo = $codigo;
    }

    /**
     * @return Usuario[]
     */
    public function getUsuarios()
    {
        $arr = array();
        foreach ($this->responsables->toArray() as $item) {
            $arr[]= $item->getUsuario();
        }
        return $arr;
    }

    /**
     * @return int
     */
    public function getNegro(): int
    {
        return $this->negro;
    }

    /**
     * @param int $negro
     */
    public function setNegro(int $negro): void
    {
        $this->negro = $negro;
    }






    // add your own fields


}
