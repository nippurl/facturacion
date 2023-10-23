<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\Usuario;
use App\Form\AgendaType;
use App\Repository\AgendaAreaRepository;
use App\Repository\AgendaRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/agenda")
 */
class AgendaController extends AbstractController
{
    const HORA_INCIO = '9:00';
    const HORA_FINAL = '21:00';

    /**
     * @Route("/", name="agenda_index", methods={"GET"})
     */
    public function index(
        AgendaRepository $agendaRepository,
        Request $request,
        AgendaAreaRepository $areaRepository
    ): Response
    {
        $fec = $request->get('fecha', 'today');
        $fecha = new \DateTime($fec);
        $areas = $areaRepository->findAll();
        $area1 = $request->get('area', $areas[0]->getId());
        $area1 = $areaRepository->find($area1);

        $agendas = $agendaRepository->findByFechaArea($fecha, $area1);
        $salto = 30;
        $horarios = $this->horarios(self::HORA_INCIO, self::HORA_FINAL, Agenda::DURACION);
        return $this->render('agenda/index.html.twig', [
            'agendas' => $agendas,
            'areas' => $areaRepository->findAll(),
            'area1' => $area1,
            'horarios' => $horarios,
            'fecha' => $fecha,
        ]);
    }

    /**
     * @Route("/new", name="agenda_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fecha = $request->get('fecha', 'today');
        $fecha = new \DateTime($fecha);
        $agenda = new Agenda($fecha);
        $hora = $request->get('hora');
        if ($hora) {
            $agenda->setHora(new \DateTime($hora));
        }
        $uId = $request->get('usuario');
        if ($uId) {
            $usuario = $entityManager->find(Usuario::class, $uId);
            $agenda->setUsuario($usuario);
        }

        $entityManager->persist($agenda);
        $b = 0;
        do {
            try {
                $entityManager->flush();
                $b = 100;
            } catch (UniqueConstraintViolationException $e) {
                if ($entityManager->isOpen() === false) {
                    $this->getDoctrine()->resetManager();
                }
                /** @var AgendaRepository $AR */
                $AR = $entityManager->getRepository(Agenda::class);
                $AR->borrar($fecha, $hora, $usuario);
                $b++;


            }
        } while ($b < 5);
        if (!$agenda->getId()) {
            throw new \Exception('NO guardo en el agenda');
        }

        return $this->redirectToRoute('agenda_edit', ['id' => $agenda->getId()]);

    }

    /**
     * @Route("/{id}/ver", name="agenda_show", methods={"GET"})
     */
    public
    function show(Agenda $agenda): Response
    {
        return $this->render('agenda/show.html.twig', [
            'agenda' => $agenda,
        ]);
    }

    /**
     * @Route("/editar/", name="agenda_edit", methods={"GET", "POST"})
     */
    public    function edit( Request $request, EntityManagerInterface $entityManager): Response
    {
        $id = $request->get('id');
        $agenda = $entityManager->find(Agenda::class, $id);
        $form = $this->createForm(AgendaType::class, $agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
            } catch (UniqueConstraintViolationException $xx) {

            }


            return $this->redirectToRoute('agenda_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agenda/edit.html.twig', [
            'agenda' => $agenda,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="agenda_delete", methods={"POST"})
     */
    public
    function delete(Request $request, Agenda $agenda, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $agenda->getId(), $request->request->get('_token'))) {
            $entityManager->remove($agenda);
            $entityManager->flush();
        }

        return $this->redirectToRoute('agenda_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     *
     * @param string $hora_incio
     * @param string $hora_final
     * @param integer $salto en minutos
     * @return \DateTime[]
     */
    private
    function horarios($hora_incio, $hora_final, $salto)
    {
        $ho = array();
        $inicio = new \DateTime($hora_incio);
        $final = new \DateTime($hora_final);
        $intervalo = new \DateInterval("PT$salto" . 'M');
        $ho[] = clone $inicio;
        while ($final > $inicio) {
            $ho[] = $inicio;
            $inicio = clone $inicio->add($intervalo);
        }
        return $ho;
    }

}
