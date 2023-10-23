<?php

namespace App\Controller;

use App\Entity\Documento;
use App\Entity\Item;
use App\Form\ItemType;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/item")
 */
class ItemController extends Controller
{
    /**
     * @Route("/doc/", name="item_doc", methods="GET")
     */
    public function index(Request $request): Response
    {
        $doc = $request->get('doc');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $documento = $em->find(Documento::class, $doc);
        if (!$documento) {
            throw  new EntityNotFoundException('No se Encontro Documento ', $doc);
        }
        $item = new Item($documento);
        $form = $this->createForm(ItemType::class, $item);


        return $this->render('item/index.html.twig', [
            'id'=>0,
            'items' => $documento->getItems(),
            'form' => $form->createView(),
            'doc' => $documento,
        ]);
    }


    /**
     * @Route("/{doc}/editar", name="item_edit", methods="GET|POST")
     * @ParamConverter("documento", class="App:Documento", options={"id" = "doc"})
     */
    public function edit(Request $request, Documento $documento): Response
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $id = $request->get('id', 0);
        /*  $item = $request->get('item' );
          /** @var Documento $doc * /
          $doc = $em->find(Documento::class,$item['docid'] );
          if (!$doc) {
              throw new EntityNotFoundException('NO existe el Doc co Id ' . $item['docid']);
          }*/
        if ($id > 0) {

            $item = $em->find(Item::class, $id);

        } else {
            $item = new Item($documento);

        }
        $form = $this->createForm(ItemType::class, $item);


        if ($request->get($form->getName())) {
            $form->submit($request->get($form->getName()));
            //      $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $documento->addItem($item);
                $em->persist($item);
                $em->flush();

                $item = new Item($documento);
                $form = $this->createForm(ItemType::class, $item);
                $id=0;
            } else {

                throw new \Exception("EL formulario vino mal");
            }
        } else {
            
        }

        return $this->render('item/index.html.twig', [
            'items' => $documento->getItems(),
            'form' => $form->createView(),
            'id' => $id,
            'doc' => $documento,
        ]);
    }

    /**
     * @Route("/borrar", name="item_delete")
     */
    public function delete(Request $request): Response
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $id = $request->get('id');
        if ($id) {
            /** @var Item $item */
            $item = $em->find(Item::class, $id);
            $doc = $item->getDocumento();
        } else {
            throw new \Exception('NO vino el ID');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();

        return $this->redirectToRoute('item_doc', ['doc' => $doc->getId()]);
    }
}
