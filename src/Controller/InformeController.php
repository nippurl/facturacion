<?php

namespace App\Controller;

use App\Entity\Comision;
use App\Entity\Documento;
use App\Entity\Gasto;
use App\Entity\Pago;
use App\Entity\PagoFormaTipo;
use App\Entity\ProductoTipo;
use App\Entity\Usuario;
use App\Form\InformeComisionType;
use App\Form\InformeDiaType;
use App\Form\Type\FechaType;
use App\Repository\ComisionRepository;
use App\Repository\ContactoRepository;
use App\Repository\DocumentoRepository;
use App\Repository\GastoRepository;
use App\Repository\ProductoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/informe", name="informe_")
 */
class InformeController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('informe/index.html.twig', [
        ]);
    }

    /**
     * @Route("/dia", name="dia")
     */
    public function dia(Request $request)
    {
        $data = [
            'desde' => new \DateTime('today'),
            'hasta' => new \DateTime('today'),
            'cajero' => Usuario::Actual(),
        ];
        $datos = $pagos = $gastos = [];


        $form = $this->createForm(InformeDiaType::class, $data);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            /** @var DocumentoRepository $DR */
            $DR = $em->getRepository(Documento::class);
            $datos = $DR->filtro($data);
            $FP = $em->getRepository(PagoFormaTipo::class)->findAll();
            /** @var PagoFormaTipo $item */
            foreach ($FP as $item) {
                $pagos[$item->getId()]['item'] = $item;
                $pagos[$item->getId()]['tot'] = 0;
            }
            /** @var Documento $documento */
            foreach ($datos as $documento) {
                /** @var Pago $pago */
                foreach ($documento->getPagos() as $pago) {
                    if ($pago->getForma()) {
                        $pagos[$pago->getForma()->getPagoForma()->getId()]['tot'] += $pago->getMonto() * $pago->getInteres();
                    }
                    }

            }
            /** @var GastoRepository $GR */
            $GR = $em->getRepository(Gasto::class);

            $gastos = $GR->findFiltro($data);

        }


        return $this->render('informe/dia.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
            'datos' => $datos,
            'pagos' => $pagos,
            'gastos' => $gastos,
        ]);
    }

    /**
     * @Route("/comision", name="comision")
     */
    public function comision(Request $request)
    {
        $form = $this->createForm(InformeComisionType::class);
        $resultdo = [];
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            /** @var ComisionRepository $CR */
            $CR = $em->getRepository(Comision::class);
            $resultdo = $CR->findComisiones($data);
        }
        return $this->render('informe/comision.html.twig', [
            'form' => $form->createView(),
            'resultado' => $resultdo,
        ]);
    }

    /**
     *
     * @Route("/comisionExt", name="comisionExt")
     * @Template()
     */
    public function comisionExtendidoAction(Request $request)
    {
        $form = $this->createForm(InformeComisionType::class);
        $resultado = [];
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            /** @var ComisionRepository $CR */

            //   $CR = $em->getRepository(Comision::class);
            //   $resultdo = $CR->findExtendido($data);
            $DR = $em->getRepository(Documento::class);
            $resultado = $DR->findComiciones($data);
            //  dump($resultado);
        }
        return $this->render('informe/comisionExt.html.twig', [
            'form' => $form->createView(),
            'resultado' => $resultado,
        ]);
    }


    /**
     * @Route("/cumpleanios", name="cumpleanios")
     */
    public function cumpleanios(Request $request, ContactoRepository $CR)
    {
        $form = $this->createFormBuilder()
            ->add('desde', FechaType::class)
            ->add('hasta', FechaType::class)
            ->add('Buscar', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        $data = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $datos = $form->getData();
            $desde = $datos['desde'];
            $hasta = $datos['hasta'];
            $data = $CR->findBycumple($desde, $hasta);
        }
        return $this->render('informe/cumpleanios.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
        ]);
    }

    /**
     * @Route("/nuevosClientes", name="nuevosClientes")
     */
    public function nuevosClientes(Request $request, ContactoRepository $CR)
    {
        $data = $resultados = [];
        $form = $this->createFormBuilder()
            ->add('desde', FechaType::class)
            ->add('buscar', SubmitType::class)
            ->getForm();
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $desde = $data['desde'];
            /** $rep Repository */

            $resultados = $CR->nuevosClientes($desde);
        }
        return $this->render('informe/nuevosClientes.html.twig', [
            'form' => $form->createView(),
            'data' => $resultados,
        ]);
    }

    /**
     * @Route("/deudores/", name="deudores")
     *
     */
    public function deudores(Request $request, DocumentoRepository $documentoRepository)
    {

        $data = $resultados = [];
        $hoy = new \DateTime();
        $default = [
            'desde' => $hoy->modify('-1 year')
        ];
        $form = $this->createFormBuilder($default)
            ->add('desde', FechaType::class)
            ->add('buscar', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $desde = $data['desde'];
            /** $rep Repository */

            $resultados = $documentoRepository->findDeudores($desde);
        }
        return $this->render('informe/deudores.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
            'resultados' => $resultados,
        ]);
}

    /**
     * Trae la lista de prouctos y su ventas 
     * @Route("/productos", name="productos")
     */
    public function productos(Request $request, ProductoRepository  $PR)
    {
        $arr = [
            'desde' => new \DateTime('-7 days')
        ];
        $form = $this->createFormBuilder($arr)
            ->add('xx', ChoiceType::class, [
                'label' => '  ',
                'choices'=> [
                    'Todos los Productos' => 0,
                     'Solo los que Tuvieron cambios de Stock' => 1,
                ],
                'required' => true,
                'expanded' => true,
            ])
            ->add('desde', FechaType::class, [
                'required' => true
             ])
            ->add('hasta', FechaType::class, [
                'required' => false,
            ])
            ->add('tipo', EntityType::class, [
                'class' => ProductoTipo::class,
                'required' => false,
            ])
            ->add('filtrar', SubmitType::class,[
                'attr' =>[
                    'class' => 'btn btn-success'
                ]
            ])
        ->getForm();
        $datos=null;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arr = $form->getData();
            $datos = $PR->findStock($arr);
        }
    return $this->render('informe/productos.html.twig', [
        'form' => $form->createView(),
        'datos' => $datos,
        'desde' => $arr['desde'],
    ]);
}
}
