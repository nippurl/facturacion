<?php

namespace App\Entity;

use Doctrine\ORM\EntityManager;
use App\Repository\PagoFormaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PagoRepository")
 */
class Pago extends Base
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Documento
     * @ORM\ManyToOne(targetEntity="App\Entity\Documento", inversedBy="pagos")
     */
    private $documento;

    /**
     * @var PagoForma
     * @ORM\ManyToOne(targetEntity="PagoForma", inversedBy="pagos" )
     * @ORM\JoinColumn(name="forma_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $forma;

    /**
     * Es el monto reconocido
     * @ORM\Column(type="float")
     * @var float
     */
    private $monto;

    /**
     * @var int
     * @ORM\Column(type="smallint" )
     */
    private $cuotas;

    /**
     * @var float
     * @ORM\Column(type="float")
     */

    private $interes;

    /**
     * @var float
     * @ORM\Column(type="float")
     */

    private $montoCuota;

    /**
     * es el monto total interes + monto reconocido (monto)
     * @var float
     * @ORM\Column(type="float")
     */

    private $montoTotal;

    public function __construct(Documento $documento)
    {
        parent::__construct();
        $this->documento = $documento;
        $this->monto = $documento->getSaldo();
        $this->forma = PagoForma::Efectivo();
        $this->calcular();
    }

    public function calcular()
    {
        if ($this->forma) {
            $this->interes = $this->forma->getInteres();
            $this->cuotas = $this->forma->getCuotas();
            $this->montoTotal = round($this->monto * $this->interes, 2);
            $this->montoCuota = round($this->montoTotal / $this->cuotas, 2);
        }
        return;
    }

    public function __toString()
    {
        return $this->monto * $this->getInteres() . " en " . $this->forma;
    }

    /**
     * @return float
     */
    public function getInteres()
    {
        return $this->interes;
    }

    /**
     * @param float $interes
     */
    public function setInteres($interes)
    {
        $this->interes = $interes;
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
     * @return Documento
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @param Documento $documento
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;
    }

    /**
     * @return PagoForma
     **/
    public function getForma()
    {
        return $this->forma;
    }

    /**
     * @param PagoForma $forma
     */
    public function setForma($forma)
    {
        if (is_numeric($forma)) {
            $rep = PagoFormaRepository::getInstance(PagoForma::class);
            $forma = $rep->find($forma);
        }
        $this->forma = $forma;
    }

    /**
     * @return float
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * @param float $monto
     */
    public function setMonto($monto)
    {
        if (strpos($monto, '%')) {
            $monto = trim(str_replace("%", "", $monto));
            $monto = $this->documento->getSaldo() * floatval($monto) / 100;
        }
        $this->monto = $monto;
    }

    /**
     * @return int
     */
    public function getCuotas()
    {
        return $this->cuotas;
    }

    /**
     * @param int $cuotas
     */
    public function setCuotas($cuotas)
    {
        $this->cuotas = $cuotas;
    }

    /**
     * @return float
     */
    public function getMontoCuota()
    {
        return $this->montoCuota;
    }

    /**
     * @param float $montoCuota
     */
    public function setMontoCuota($montoCuota)
    {
        $this->montoCuota = $montoCuota;
    }

    /**
     * @return float
     */
    public function getMontoTotal()
    {
        return $this->monto * $this->interes;
    }

    /**
     * @param float $montoTotal
     */
    public function setMontoTotal($montoTotal)
    {
        $this->montoTotal = $montoTotal;
    }

    /**
     * @return mixed
     */
    public function getDocumentoId(): int
    {
        return $this->documento->getId();
    }

    /**
     * @param mixed $documentoId
     */
    public function setDocumentoId(int $documentoId): Pago
    {
        if (is_numeric($documentoId)) {
            global $kernel;
            if ('AppCache' == get_class($kernel)) {
                $kernel = $kernel->getKernel();
            }
            $container = $kernel->getContainer();
            /* @var $container  \Symfony\Component\DependencyInjection\Container */
            /** @var EntityManager $em */
            $em = $container->get('doctrine.orm.default_entity_manager');
            $this->documento = $em->find(Documento::class, $documentoId);
        }
        return $this;
    }

    public function getInteresStr(): float
    {
        $xx = round(($this->interes - 1) * 100);
        return $xx;
    }
}
