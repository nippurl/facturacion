<?php

namespace App\Controller;

use App\Entity\Voucher;
use App\Entity\VoucherCanje;
use App\Form\VoucherCanjeType;
use App\Form\VoucherType;
use App\Repository\VoucherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/voucher")
 */
class VoucherController extends AbstractController
{
    /**
     * @Route("/", name="voucher_index", methods={"GET"})
     */
    public function index(VoucherRepository $voucherRepository, Request $request): Response
    {

        if ($request->get('q')) {
            $q = $request->get('q');
            $vales = $voucherRepository->findAllINv($q);
        }else{
            $vales = $voucherRepository->findAllINv();
        }
        return $this->render('voucher/index.html.twig', [
            'vouchers' => $vales,
        ]);
    }

    /**
     * @Route("/new", name="voucher_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $voucher = new Voucher();
        $form = $this->createForm(VoucherType::class, $voucher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($voucher);
            $entityManager->flush();

            return $this->redirectToRoute('voucher_index');
        }

        return $this->render('voucher/new.html.twig', [
            'voucher' => $voucher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="voucher_show", methods={"GET"})
     */
    public function show(Voucher $voucher): Response
    {
        return $this->render('voucher/show.html.twig', [
            'voucher' => $voucher,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="voucher_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Voucher $voucher): Response
    {
        $form = $this->createForm(VoucherType::class, $voucher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('voucher_index');
        }

        return $this->render('voucher/edit.html.twig', [
            'voucher' => $voucher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="voucher_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Voucher $voucher): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voucher->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($voucher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('voucher_index');
    }

    /**
     * @Route("/canjear/{id}/canjear", name="voucher_canjear")
     */
    public function canjear(Request $request, Voucher $voucher)
    {
        $canje = new VoucherCanje($voucher);


       $voucher->setCanjeFecha(new \DateTime());

        $form = $this->createForm(VoucherCanjeType::class, $canje);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->persist($canje);
           $em->flush();

            return $this->redirectToRoute('voucher_index');
        }

        return $this->render('voucher/edit.html.twig', [
            'voucher' => $voucher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Genera la pantalla de impresion del Voucher
     * @Route("/imprimir/{id}", name="voucher_imprimir")
     *
     */
    public function imprimir(Voucher $voucher, EntityManagerInterface $entityManager)
    {
        $voucher->impreso();
        $entityManager->persist($voucher);
        $entityManager->flush();

           return $this->render('voucher/imprimir.html.twig', [
                'voucher' => $voucher,
           ]);
    }
}
