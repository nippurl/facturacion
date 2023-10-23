<?php

namespace App\Controller;

use App\Entity\Documento;
use App\Entity\Pago;
use App\Entity\PagoForma;
use App\Form\PagoType;
use App\Repository\PagoRepository;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pago")
 */
class PagoController extends Controller
{
    /**
     * @Route("/{doc}/pagos", name="pago_index", methods="GET")
     * @ParamConverter("documento", class="App:Documento", options={"id" = "doc"})
     */
    public function index(PagoRepository $pagoRepository, Documento $documento): Response
    {
        $pagos = $documento->getPagos();
        $pago = new  Pago($documento);
        $form = $this->createForm(PagoType::class, $pago);
        return $this->render('pago/index.html.twig', [
            'pagos' => $pagos,
            'id' => 0,
            'form' => $form->createView(),
            'doc'=>$documento,
        ]);
    }


    /**
     * @Route("/{doc}/edit", name="pago_edit", methods="GET|POST")
     * @ParamConverter("documento", class="App:Documento", options={"id" = "doc"})
     */
    public function edit( Documento $documento ,Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id', 0);
        if ($id) {
            $pago = $em->find(Pago::class, $id);

        } else {
            $pago = new Pago($documento);
        }

        $form = $this->createForm(PagoType::class, $pago);

       // $form->handleRequest($request);
            $form->submit($request->get($form->getName()));

        if ($form->isSubmitted() && $form->isValid()) {
            $pago->calcular();
            $em->persist($pago);
            $em->flush();

        }
        return $this->redirectToRoute('pago_index', ['doc' => $pago->getDocumento()->getId()]);

    }

    /**
     * @Route("/borrar/", name="pago_delete")
     */
    public function delete(Request $request ): Response
    {
         /** @var \Doctrine\ORM\EntityManager $em */
          $em = $this->getDoctrine()->getManager();

         $id = $request->get('id');
        if ($id) {
            /** @var Item $pago */
            $pago = $em->find(Pago::class, $id);
            $doc = $pago->getDocumento();
        } else {
    throw new \Exception('NO vino el ID');
}
        $doc = $pago->getDocumento();

        $em = $this->getDoctrine()->getManager();
        $em->remove($pago);
        $em->flush();

        return $this->redirectToRoute('pago_index', ['doc' => $doc->getId()]);
    }

    /**
     * @Route("/forma", name="pago_forma")
     * @param PagoForma $forma
     * @return Response
     */
    public function forma(Request $request)
    {
         /** @var \Doctrine\ORM\EntityManager $em */
          $em = $this->getDoctrine()->getManager();
          $id = $request->get('id');
          $forma = $em->find(PagoForma::class, $id);
        if (!$forma) {
            throw  new EntityNotFoundException('No se encontro');
          }
        $arr = [
            'interes' => $forma->getInteres(),
            'cuotas' => $forma->getCuotas(),

        ];
        $response = new Response();
        $response->setContent(json_encode($arr));
        return  $response;
    }
}
