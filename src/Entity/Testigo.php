<?php

namespace App\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\TestigoRepository")
 */
class Testigo
{
    const SEPARADOR = ': ';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var Usuario
     * @ORM\ManyToOne(targetEntity="Usuario")
     *
     *   */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle", type="text")
     */
    private $detalle;

    /**
     * @var string
     *
     * @ORM\Column(name="objeto", type="string", length=100, nullable=true)
     */
    private $objeto;
    /**
     *
     * @ORM\Column(name="obejto_id", type="bigint", nullable=true)
     */
    private $objetoId;

    /**
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel = null)
    {
        $this->setCreatedAt(new \DateTime());

        if ($kernel !== null) {
            $container = $kernel->getContainer();
            /* @var $container  \Symfony\Component\DependencyInjection\Container */
            /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\tokenStorage $token */

            $token = $container->get('security.token_storage');
            if ($token->getToken()) {
                $user = $token->getToken()->getUser();

                // }else{
                //    $user = $token->get()
            }

        } else {
            $user = Usuario::Actual();
        }
        if (is_object($user)) {
            $this->setUsuario($user);
        }

    }

    /**
     * @param String $texto
     * @return Testigo
     */
    static function registar($texto)
    {
        /** @var Kernel $kernel */
        global $kernel;
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        /** @var EntityManager $em */
        $em = $kernel->getContainer()
            ->get('doctrine.orm.entity_manager');
        /** @var Testigo $testigo */
        $testigo = new Testigo($kernel);
        $testigo->setDetalle($texto);
        $em->persist($testigo);
        $em->flush();

        return $testigo;
    }

    /**
     * Registra un objeto en la base de datos
     * @param object $objeto
     * @param string $mensaje
     */
    static public function RegistrarObjeto($objeto, $mensaje)
    {

        // var_dump("Testigo");
        // try {

        /** @var Kernel $kernel */
        //   var_dump($objeto);
        global $kernel;
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        /** @var EntityManager $em */
        $em = $kernel->getContainer()
            ->get('doctrine.orm.entity_manager');
        /** @var Testigo $testigo */
        $testigo = new Testigo($kernel);

        // Analiis del objeto
        // El primero es muy lento
        //       $xx = json_encode(Debug::export($objeto,1));
        //    $xx = json_encode((array)$objeto);
        $array = [];
        $array = $testigo->objToArray($objeto, $array, 1);
        $xx = json_encode($array);

        $testigo->setDetalle($mensaje . self::SEPARADOR . $xx);
        $testigo->objeto = get_class($objeto);
        $testigo->objetoId = $objeto->getId();
        $em->persist($testigo);
        $em->flush();
        //   }catch (Exception $x){

        //   }

    }

    public static function objToArray($obj, &$arr = [], $nivel)
    {

        if (!is_object($obj) && !is_array($obj)) {
            $arr = $obj;
            return $arr;
        }
        if ($obj instanceof \DateTime) {
            $arr = $obj->format('d.m.Y H:i:s');
            return $arr;
        }
        if ($obj instanceof PersistentCollection) {
            $arr = [];
            foreach ($obj as $item) {
                $arr[] = $item->getId();
            }

            return $arr;
        }

        if ($nivel > 0) {   //  $obj = (array) $obj;
            foreach ((array)$obj as $key => $value) {

                $arr[$key] = [];
                $arr[$key] = self::objToArray($value, $arr[$key], $nivel - 1);


            }
        } else {
            if (is_array($obj)) {
                $arr = "array";
                //    return $arr;
            }
            if (is_object($obj)) {
                if (method_exists($obj, 'getId')) {
                    $arr = "ID=" . $obj->getId();
                } else if (method_exists($obj, '__toString')) {
                    $arr = $obj->__toString();
                } else {
                    $arr = 'OBJ';
                }
                //  $arr[$key] = $value;
                //    return $arr;
            }
            // $arr[$key]="Obj";
        }
        return $arr;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt tiempo de pratica
     * @return Testigo
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set usuario
     *
     * @param Usuario $usuario
     * @return Testigo
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     * @return Testigo
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get objeto
     *
     * @return string
     */
    public function getObjeto()
    {
        return $this->objeto;
    }

    /**
     * Set objeto
     *
     * @param string $objeto
     *
     * @return Testigo
     */
    public function setObjeto($objeto)
    {
        $this->objeto = $objeto;

        return $this;
    }

    /**
     * Get objetoId
     *
     * @return integer
     */
    public function getObjetoId()
    {
        return $this->objetoId;
    }

    /**
     * Set objetoId
     *
     * @param integer $objetoId
     *
     * @return Testigo
     */
    public function setObjetoId($objetoId)
    {
        $this->objetoId = $objetoId;

        return $this;
    }

    public function getAccion()
    {
        $mat = explode(self::SEPARADOR, $this->detalle, 2);
        return $mat[0];
    }

    public function getDatos()
    {
        $mat = explode(self::SEPARADOR, $this->detalle, 2);
        return json_decode($mat[1], 1);
    }
}
/*
 *
$tet = new Testigo();
$array =[];
print_r(json_encode($tet->objToArray($tet,$array,2)));

 */