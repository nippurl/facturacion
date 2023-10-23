<?php

namespace App\Controller;

use App\Entity\Contacto;
use App\Entity\Documento;
use App\Repository\ContactoRepository;
use App\Repository\DocumentoTipoRepository;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="index_")
 */
class IndexController extends Controller
{
    /**
     * @Route("", name="index")
     */
    public function index(MenuRepository $MR)
    {
        $menus = $MR->findAll();
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'menus' => $menus
        ]);
    }

    /**
     * @Route("/buscar", name="buscar")
     */
    public function buscar(Request $request, EntityManagerInterface $em)
    {
        $q = trim($request->get('q'));
        $docs = [];
        $clis = [];
        if ($q) {
            if (is_numeric($q)) {
                // Bsucar Comprobantes
                /** @var DocumentoRepository $DR */
                $DR = $em->getRepository(Documento::class);
                $docs = $DR->findByNumero($q);
            }
            /** @var ContactoRepository $CR */
            $CR = $em->getRepository(Contacto::class);
            $clis = $CR->findByApenom($q);
        }
        return $this->render('index/buscar.html.twig', [
            'docs'=>$docs,
            'clis' => $clis,
        ]);
    }

}
