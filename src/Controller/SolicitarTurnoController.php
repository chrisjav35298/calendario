<?php

namespace App\Controller;

use App\Entity\SolicitarTurno;
use App\Form\SolicitarTurnoType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SolicitarTurnoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/solicitar/turno")
 */
class SolicitarTurnoController extends AbstractController
{
    /**
     * @Route("/", name="app_solicitar_turno_index", methods={"GET"})
     */
    public function index(SolicitarTurnoRepository $solicitarTurnoRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $solicitarTurnoRepository->createQueryBuilder('st')
        ->orderBy('st.id', 'DESC') // Ordenar por fecha descendente
        ->getQuery();
        
       
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                7 /*limit per page*/
            );

      


        return $this->render('solicitar_turno/index.html.twig', [
            // 'solicitar_turnos' => $totalTurnos, 
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/new", name="app_solicitar_turno_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,SolicitarTurnoRepository $solicitarTurnoRepository): Response
    {
        $fechasOcupadas=$solicitarTurnoRepository->findByMesPosterior();
        $turnosOcupados = [];
        foreach ($fechasOcupadas as $turno) {
            $turnosOcupados[] = $turno->getFecha()->format('Y-m-d');
        }
        $solicitarTurno = new SolicitarTurno();
        $form = $this->createForm(SolicitarTurnoType::class, $solicitarTurno);
        $form->handleRequest($request);
     

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitarTurno->setEstado(1);
            $entityManager->persist($solicitarTurno);
            $entityManager->flush();

            return $this->redirectToRoute('app_solicitar_turno_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('solicitar_turno/new.html.twig', [
            'solicitar_turno' => $solicitarTurno,
            'form' => $form,
            'turnosOcupados'=>$turnosOcupados,
        ]);
    }
    /**
     * @Route("/{id}", name="app_solicitar_turno_show", methods={"GET"})
     */
    public function show(SolicitarTurno $solicitarTurno): Response
    {
        return $this->render('solicitar_turno/show.html.twig', [
            'solicitar_turno' => $solicitarTurno,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_solicitar_turno_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SolicitarTurno $solicitarTurno, SolicitarTurnoRepository $solicitarTurnoRepository): Response
    {
        $form = $this->createForm(SolicitarTurnoType::class, $solicitarTurno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitarTurnoRepository->add($solicitarTurno, true);

            return $this->redirectToRoute('app_solicitar_turno_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('solicitar_turno/edit.html.twig', [
            'solicitar_turno' => $solicitarTurno,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_solicitar_turno_delete", methods={"POST"})
     */
    public function delete(Request $request, SolicitarTurno $solicitarTurno, SolicitarTurnoRepository $solicitarTurnoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solicitarTurno->getId(), $request->request->get('_token'))) {
            $solicitarTurnoRepository->remove($solicitarTurno, true);
        }

        return $this->redirectToRoute('app_solicitar_turno_index', [], Response::HTTP_SEE_OTHER);
    }
}
