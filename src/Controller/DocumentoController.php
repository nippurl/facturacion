<?php

namespace App\Controller;

use App\Entity\Documento;
use App\Entity\DocumentoTipo;
use App\Entity\Pago;
use App\Entity\PagoForma;
use App\Entity\Usuario;
use App\Form\DocCabType;
use App\Repository\DocumentoRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/documento", name="documento_")
 */
class DocumentoController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('documento/index.html.twig', [
            'controller_name' => 'DocumentoController',
        ]);
    }

    /**
     * @Route("/nuevo/{tipo}", name="nuevo")
     *
     */
    public function nuevo(Request $request, $tipo, EntityManagerInterface $em)
    {

        // Primero antes que nada revisar si abrio la caja
//        if ($this->hasParameter('caja_inicial')) {
//         $cajaInical =   $this->getParameter('caja_inicial',false);
//       }else{
//            $cajaInical = false;
//        }
        $cajaInical = $this->getParameter('caja_inicial',false);
        if ($cajaInical) {
            /** @var Usuario $suer */
            $user  = $this->getUser();
            //Revisar si existe una caja de usuario de hoy
            /** @var DocumentoRepository $DR */
            $DR = $em->getRepository(Documento::class);
            $hoy = new DateTime('today');
            /** @var Documento[] $cajaInicalHoy */
            $cajaInicalHoy=$DR->getCajaInicial($user, $hoy);
            // Si no hay incio de caja, para para iniciar caja
            if (empty($cajaInicalHoy)) {
              return   $this->redirectToRoute('documento_caja_inicial');
            }

        }
        

        /** @var \Doctrine\ORM\EntityManager $em */
        $docTipo = $em->find(DocumentoTipo::class, $tipo);
        $doc = new Documento($docTipo);
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->persist($doc);
        $em->flush();
        //   var_dump($doc);
        $form = $this->createForm(DocCabType::class, $doc, [
            'action' => $this->generateUrl('documento_cerrar', [

                'id' => $doc->getId(),
            ]),
            'attr' => [
                'id' => 'formulario',
            ]
            ,

        ]);


        return $this->render('documento/editar.html.twig', [
            'doc' => $doc,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/cerrar/{id}", name="cerrar")
     */
    public function cerrar(Documento $documento, Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(DocCabType::class, $documento);

        $form->handleRequest($request);

        $documento->concretar($em);
        $em->persist($documento);
        $em->flush();

        return $this->render('documento/cerrar.html.twig', [
            'documento' => $documento,
        ]);
    }

    /**
     * @Route("/ver/{id}", name="ver")
     */
    public function ver(Request $request, Documento $documento)
    {

        return $this->render('documento/ver.html.twig', [
            'doc' => $documento,
        ]);
    }

    /**
     * @Route("/editar/{docId}", name="editar")
     *
     */
    public function editar(Request $request, $docId)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $doc = $em->find(Documento::class, $docId);
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->persist($doc);
        $em->flush();
        //   var_dump($doc);
        $form = $this->createForm(DocCabType::class, $doc, [
            'action' => '#',
        ]);


        //$form->handleRequest($request);
        if ($request->get($form->getName())) {
            $form->submit($request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $doc->concretar($em);
            $em->persist($doc);
            $em->flush();
            if ($request->get('imprimir',false)){
                return $this->redirectToRoute('documento_imprimir', ['docId'=> $doc->getId()]);
            }else{
                return $this->redirectToRoute('index_index', []);
            }

        }

        return $this->render('documento/editar.html.twig', [
            'doc' => $doc,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/borrar/{docId}", name="borrar")
     */
    public function borrar(Request $request, Documento $doc)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $doc->borrar();
        $doc->prePersist($doc);
        $em->flush();
        return $this->render('documento/borrar.html.twig', [

        ]);
    }

    /**
     * @Route("/imprimir/{docId}", name="imprimir")
     *
     *
     */

    public function ImprimirAction($docId, DocumentoRepository $DR)
    {
        $doc = $DR->find($docId);

        // Configure Dompdf según sus necesidades
        $pdfOptions = new Options();
        $pdfOptions->set([
            'defaultFont', 'Arial',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,

        ]);

        // Crea una instancia de Dompdf con nuestras opciones
        $dompdf = new Dompdf($pdfOptions);

        // Recupere el HTML generado en nuestro archivo twig
        $html = $this->renderView('documento/imprimir.html.twig', [
            'doc' => $doc,
        ]);

        // Cargar HTML en Dompdf
        $dompdf->loadHtml($html);

        // (Opcional) Configure el tamaño del papel y la orientación 'vertical' o 'vertical'
        $dompdf->setPaper('A4', 'portrait');

        // Renderiza el HTML como PDF
        $dompdf->render();
        $comp = $doc->getNumero();
        // Output the generated PDF to Browser (force download)
        $dompdf->stream("comprobante-$comp.pdf", [
            "Attachment" => true,
        ]);
        /**/
        $response = new Response();

        $response->setContent($dompdf->output());
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;

    }

    /**
     * @Route("/imprimirView/{id}", name="documento_imprimirView")
     *
     */
    public function imprimirView(Documento $doc)
    {
       return  $this->render('documento/imprimir.html.twig', [
            'doc' => $doc,
        ]);

    }

    /**
     * @Route("/cancelar/{id}", name="cancelar")
     */
    public function cancelar(Documento $doc,EntityManagerInterface $entityManager)
    {
        $doc->cancelar();
        $entityManager->persist($doc);
        $entityManager->flush();
        return $this->render('documento/ver.html.twig', [
            'doc' => $doc,
        ]);
    }

    /**
     * Este metodo sirve para crear una caja inciial al comienzo del trabajo
     * Es para agregar al comiento en la caja del dia
     * solo lleva un campo el total de caja al inicio
     * @Route("/caja_inicial", name="caja_inicial")
     */
    public function caja_inical(Request $request, EntityManagerInterface $em)
    {
        $arr=[
            'monto' =>0,
        ];
        $form = $this->createFormBuilder($arr)
            ->add('monto', NumberType::class,[
                'label' => 'Cuanta Plata hay en la caja para iniciar',

            ])
            ->add('Guardar', SubmitType::class,[
                'attr'=>[
                    'class' => 'btn btn-success'
                ]
            ])
        ->getForm();

        $form->handleRequest($request);
        $tipo = $em->find(DocumentoTipo::class, Documento::CAJA_INICIAL);

        if ($form->isSubmitted() && $form->isValid()) {
            $tipo = $em->find(DocumentoTipo::class, Documento::CAJA_INICIAL);
           // Si no existe el tipo lo crea
            if (empty($tipo)) {
                $sql = "set NO_AUTO_VALUE_ON_ZERO =1;
INSERT INTO documento_tipo (id, letra, ultimo, caja, stock, menu, nombre,
                            pagos, blanco, ad_orden, menu_imagen, voucher, create_by_id, update_by_id,
                            create_at, update_at, deposito_id) VALUES ('0', 'I', NULL, '1', '0',
                    NULL, 'INICIO DE CAJA', '1', '1', NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL);";
                $em->getConnection()->executeUpdate($sql);
                $tipo = $em->find(DocumentoTipo::class, Documento::CAJA_INICIAL);
            }
            // DEbo crear un documento con el inicio de caja con un pago en efectivo de monto
            $doc = new Documento($tipo);
            $doc->setEstado(Documento::Cerrado);
            $doc->setTotal($form->get('monto')->getData());
            /** @var Pago $pago */
            $pago = new Pago($doc);

            $pago->setForma(PagoForma::EFECTIVO);
            $pago->setMonto($form->get('monto')->getData());
            $em->persist($doc);
            $em->persist($pago);
            $em->flush();
            return $this->redirectToRoute('index_index', []);


        }
        return $this->render('documento/caja_inical.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}