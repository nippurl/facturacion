<?php

namespace App\Controller;

use App\Entity\DocumentoTipo;
use App\Repository\DocumentoTipoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/documento/tipo", name="documentoTipo_")
 */
class DocumentoTipoController extends Controller
{
    /**
     * @Route("/documento/tipo", name="index")
     */
    public function index()
    {
        return $this->render('documento_tipo/index.html.twig', [
            'controller_name' => 'DocumentoTipoController',
        ]);
    }


    /**
     * genera la lisma del menu
     * @Route("/menu", name="menu")
     */
    public function menu(Request $request)
    {
        $user = $this->getUser();
         /** @var \Doctrine\ORM\EntityManager $em */
          $em = $this->getDoctrine()->getManager();
          /** @var DocumentoTipoRepository $DTR */
          $DTR = $em->getRepository(DocumentoTipo::class);
        $dtipo = $DTR->findMenu($user);
        return $this->render('documento_tipo/menu.html.twig', [
            'dtipo' => $dtipo,
        ]);
    }
}
