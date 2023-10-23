<?php
/**
 * Created by PhpStorm.
 * User: jme
 * Date: 24/03/18
 * Time: 13:33
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * Abstract base class to be extended by my entity classes with same fields
 *
 * @MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class Base
{
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createAt;
    /**
     * @var Usuario
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     */
    protected $createBy;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updateAt;
    /**
     * @var Usuario
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     */
    protected $updateBy;


    public function __construct()
    {
        $this->createAt = new \DateTime();
        $this->createBy = Usuario::Actual();
        $this->updateAt = new \DateTime();
        $this->updateBy = Usuario::Actual();
    }


    /**
     * @ORM\PostPersist()
     */
    public function postPersist (){
        Testigo::RegistrarObjeto($this, 'Nuevo');

    }

    /**
     * @ORM\PostUpdate()
     */
    public function postUpdate()
    {
        Testigo::RegistrarObjeto($this, 'Modifico');
        return;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate (){
        $this->updateAt = new \DateTime();
        $this->updateBy = Usuario::Actual();
     return ;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createAt = new \DateTime();
        $this->createBy = Usuario::Actual();
        $this->updateAt = new \DateTime();
        $this->updateBy = Usuario::Actual();
        return;
    }

}