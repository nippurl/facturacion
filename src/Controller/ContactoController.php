<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\Contacto;
use App\Entity\Documento;
use App\Form\ContactoType;
use App\Repository\ContactoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contacto")
 */
class ContactoController extends Controller
{
    /**
     * @Route("/", name="contacto_index", methods="GET")
     */
    public function index(ContactoRepository $contactoRepository): Response
    {
        return $this->render('contacto/index.html.twig', ['contactos' => $contactoRepository->findAll()]);
    }

    /**
     * @Route("/new", name="contacto_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $contacto = new Contacto();
        $form = $this->createForm(ContactoType::class, $contacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contacto);
            $em->flush();

            return $this->redirectToRoute('contacto_index');
        }

        return $this->render('contacto/new.html.twig', [
            'contacto' => $contacto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/ver", name="contacto_show", methods="GET")
     */
    public function show(Contacto $contacto): Response
    {
        return $this->render('contacto/show.html.twig', ['contacto' => $contacto]);
    }

    /**
     * @Route("/{id}/edit", name="contacto_edit", methods="GET|POST")
     */
    public function edit(Request $request, Contacto $contacto): Response
    {
        $form = $this->createForm(ContactoType::class, $contacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contacto_edit', ['id' => $contacto->getId()]);
        }

        return $this->render('contacto/edit.html.twig', [
            'contacto' => $contacto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/borrar", name="contacto_delete")
     */
    public function delete(Request $request, Contacto $contacto): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$contacto->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('contacto_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($contacto);
        $em->flush();

        return $this->redirectToRoute('contacto_index');
    }

    /**
     * @Route("/ajax", name="contacto_ajax")
     */
    public function ajax(Request $request)
    {
         /** @var \Doctrine\ORM\EntityManager $em */
          $em = $this->getDoctrine()->getManager();
          
       $id = $request->get('id');
       $did =  $request->get('did');
       $doc = $em->find(Documento::class, $did);

       $Contacto = $em->find(Contacto::class,$id);
       if (!$Contacto){
           throw new Exception('NO DE ENCONTRO A Contacto con id '.$id);
       }else{
           $doc->setContacto($Contacto);
           $em->persist($doc);
           $em->flush();
       }

       return $this->render('contacto/datosDoc.hrml.twig', [
           'contacto'=> $Contacto,
       ]);

    }

    /**
     * @param did  -> documento id
     * @param aid  -> agenda id
     * @Route("/newAjax/", name="contacto_newAjax")
     */
    public function newAjax(Request $request, EntityManagerInterface $em, LoggerInterface $loger)
    {
        $doc = $agen =null;
        $contacto = new Contacto();
        $did = $request->get('did');
        if ($did) {

            $doc = $em->find(Documento::class, $did);
            if (!$doc) {
                $loger->alert('No Viene con numeor de documento ');
            }
        }
        $aid = $request->get('aid');
        if ($aid) {
            $agen = $em->find(Agenda::class, $aid);
            if (!$agen) {
                $loger->alert('No Viene con numeor de documento ');
            }
        }
        $form = $this->createForm(ContactoType::class, $contacto, [
          //  'action'=>$this->generateUrl('contacto_newAjax')
            'action'=> 'javascript:guardarContacto();',
            'attr'=>array('novalidate'=>'novalidate')
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             /** @var \Doctrine\ORM\EntityManager $em */

              $em->persist($contacto);
              $em->flush();
            if ($doc) {
                $doc->setContacto($contacto);
              }
            if ($agen) {
                $agen->setContacto($contacto);
             }


            $resp = new JsonResponse(array(
                'id' => $contacto->getId(),
                'nombre' => $contacto->getRazon(),
            ));
            return $resp;
        }
        return $this->render('contacto/newAjax.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/findAjax", name="contacto_findAjax")
     */
    public function findAjax(Request $request, ContactoRepository $CR)
    {
        $temm = $request->get('search');
        if ($temm) {
            $contactos = $CR->findByApenom($temm, 50);
        } else {
            $contactos = $CR->createQueryBuilder('c')
                ->setMaxResults(50)
                ->getQuery()->execute();
        }
        $arr = [];
        foreach ($contactos as $contacto) {
            $arr[] = [
                'id'   => $contacto->getId(),
                'text' => $contacto->__toString(),
            ];
            //  $arr[$contacto->getId()] =  $contacto->__toString();
        }
        $response = new JsonResponse();
        $response->setData(["results" => $arr]);
        $response->headers->set('Content-Type', 'application/json');
        return $response;


    }

}
