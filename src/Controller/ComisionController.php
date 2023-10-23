<?php

namespace App\Controller;

use App\Entity\Comision;
use App\Entity\Producto;
use App\Entity\Usuario;
use App\Form\ComisionFilterType;
use App\Form\ComisionMasivoType;
use App\Form\ComisionType;
use App\Repository\ComisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comision")
 */
class ComisionController extends Controller
{
    /**
     * @Route("/", name="comision_index", methods="GET|POST")
     */
    public function index(ComisionRepository $comisionRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $page =  1;
        $cant= $Maxpage = 0;
        $data = [
            'producto' => null,
            'vendedor' => null,
        ];
        $dataLink = [];
        $b = 0;
        if ($request->get('proId')) {
            $producto = $entityManager->find(Producto::class, $request->get('prodId'));
            $data['producto'] = $producto;
            $dataLink['prodId'] = $producto->getId();
            $b = 1;

        }else{
            $dataLink['prodId'] = null;
        }

        if ($request->get('venId')) {
            $vendedor = $entityManager->find(Usuario::class, $request->get('venId'));
            $data['vendedor'] = $vendedor;
            $dataLink['venId'] = $vendedor->getId();
            $b = 1;
        }else{
            $dataLink['venId'] = null;
        }

        if ($request->get('page')) {
            $page = $request->get('page');

        }
        $form = $this->createForm(ComisionFilterType::class, $data, [
            'method' => 'POST',
            'action' => '#'
        ]);


        $form->handleRequest($request);
        if ($b || ($form->isSubmitted() && $form->isValid())) {
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
            }

            if ($data['producto']) {
                $dataLink['prodId'] = $data['producto']->getId();
            }
            if ($data['vendedor']) {
                $dataLink['venId'] = $data['vendedor']->getId();
            }


            $comisiones = $comisionRepository->filtro($data, $page);
            $cant = $comisionRepository->getCantFiltro($data);
            $Maxpage = ceil($cant / $comisionRepository::CANT);


        } else {
            $comisiones = [];

        }



        //dump($data);
        $comisionRepository->crearFaltantes();
        return $this->render('comision/index.html.twig', [
            'comisions' => $comisiones,
            'form' => $form->createView(),
            'data' => $dataLink,
            'MaxPage' => $Maxpage,
            'page' => $page,
            'cant' => $cant,

        ]);
    }

    /**
     * @Route("/guardar/", name="comision_guardar")
     *
     */
    public function guardar(Request $request, ComisionRepository $CR)
    {

        $com = $request->get('com');
        foreach ($com as $id => $comison) {
            $a = explode('_', $id);
            $proId = $a[0];
            $venId = $a[1];
            $comison = $comison / 100;
            //// buscar la comision
            $CR->cambiar($proId, $venId, $comison);

        }
        $proId = $request->get('proId');
        $venId = $request->get('venId');
        return $this->redirectToRoute('comision_index', [
            'proId' => $proId,
            'venId' => $venId,

        ]);
    }

    /**
     * @Route("/new", name="comision_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $comision = new Comision();
        $form = $this->createForm(ComisionType::class, $comision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comision);
            $em->flush();

            return $this->redirectToRoute('comision_index');
        }

        return $this->render('comision/new.html.twig', [
            'comision' => $comision,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/ver", name="comision_show", methods="GET")
     */
    public function show(Comision $comision): Response
    {
        return $this->render('comision/show.html.twig', ['comision' => $comision]);
    }

    /**
     * @Route("/{id}/edit", name="comision_edit", methods="GET|POST")
     */
    public function edit(Request $request, Comision $comision): Response
    {
        $form = $this->createForm(ComisionType::class, $comision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comision_edit', ['id' => $comision->getId()]);
        }

        return $this->render('comision/edit.html.twig', [
            'comision' => $comision,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comision_delete", methods="DELETE")
     */
    public function delete(Request $request, Comision $comision): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comision->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comision);
            $em->flush();
        }

        return $this->redirectToRoute('comision_index');
    }

    /**
     * @Route("/masivo1", name="comision_masivo1")
     */
    public function masivo1(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $comisionRepository = $em->getRepository(Comision::class);
        $data = $request->get('data');
        $form = $this->createForm(ComisionMasivoType::class, $data, [
            'filtro' => $data,
        ]);
        return $this->render('comision/masivo1.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
        ]);
    }

    /**
     * @Route("/masivo2", name="comision_masivo2")
     */
    public function masivo2(Request $request)
    {
        $data = [];
        $form = $this->createForm(ComisionMasivoType::class, $data);
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $com = $data['comision'];
            /** @var Comision $comision */
            foreach ($data['comisiones'] as $comision) {
                if ($data['incremento'] != 0) {
                    $com = $comision->getComision() * (1 + $data['incremento']);
                }
                $comision->setComision($com);
                $em->persist($comision);
            }
        } else {
            throw new \Exception('No paso el Formulario');
        }
        $em->flush();


        return $this->render('comision/masivo2.html.twig', ['comisiones' => $data['comisiones'],]);
    }
}
