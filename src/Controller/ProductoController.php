<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Entity\ProductoTipo;
use App\Entity\Stock;
use App\Form\ProductoType;
use App\Repository\ProductoRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/producto")
 */
class ProductoController extends Controller
{
    /**
     * @Route("/", name="producto_index", methods="GET")
     */
    public function index(ProductoRepository $productoRepository, Request $request): Response
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $SR = $em->getRepository(Stock::class);
        /** $SR StockRepository */
        $SR->control();
        $form = $this->createFormBuilder()
            ->setMethod('GET')
            ->add('q',null,[
                'label' => 'Filtro por Nombre',
                'required' => false,
            ])
            ->add('tipo', EntityType::class,[
                'class'=>ProductoTipo::class,
                'required' => false,

            ])
            ->add('visible',ChoiceType::class,[
                'choices' => array_flip([0 => 'TODOS',
                    1 => 'Solo Visibles'
                ]),
            ])
            ->add('filtrar',SubmitType::class,[
                'attr'=> ['class'=>'btn btn-success']
            ])
        ->getForm();
        $productos = [];
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $productos = $productoRepository->findPro($data);
        }else{
            $productos = $productoRepository->findAll();
        }

        return $this->render('producto/index.html.twig', [
            'productos' => $productos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="producto_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $producto = new Producto();
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($producto);
            $em->flush();

            return $this->redirectToRoute('producto_index');
        }

        return $this->render('producto/new.html.twig', [
            'producto' => $producto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/ver", name="producto_show", methods="GET")
     */
    public function show(Producto $producto): Response
    {
        return $this->render('producto/show.html.twig', ['producto' => $producto]);
    }

    /**
     * @Route("/{id}/edit", name="producto_edit", methods="GET|POST")
     */
    public function edit(Request $request, Producto $producto): Response
    {
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('producto_edit', ['id' => $producto->getId()]);
        }

        return $this->render('producto/edit.html.twig', [
            'producto' => $producto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/borrar", name="producto_delete")
     */
    public function delete(Request $request, Producto $producto): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $producto->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('producto_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($producto);
        $em->flush();

        return $this->redirectToRoute('producto_index');
    }

    /**
     * @Route("/ajax/", name="producto_ajax")
     */
    public function ajax(Request $request)
    {
        $id = $request->get('id');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $prod = $em->find(Producto::class, $id);

        if (!$prod) {
            throw new EntityNotFoundException("NO SE ECONTRO EL PRODUCTO CON ID " . $id);
        }
        $response = new Response();
        $response->setContent(json_encode(array(
            'descripcion' => $prod->getDescripcion(),
            'precio' => $prod->getPrecio(),
        )));

        return $response;
    }
}
