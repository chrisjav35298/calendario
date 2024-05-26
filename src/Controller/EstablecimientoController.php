<?php

namespace App\Controller;

use App\Entity\Establecimiento;
use App\Form\EstablecimientoType;
use App\Repository\EstablecimientoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/establecimiento")
 */
class EstablecimientoController extends AbstractController
{
    /**
     * @Route("/", name="app_establecimiento_index", methods={"GET"})
     */
    public function index(EstablecimientoRepository $establecimientoRepository): Response
    {
        return $this->render('establecimiento/index.html.twig', [
            'establecimientos' => $establecimientoRepository->findAll(),
        ]);
    }

    //http://localhost:8001/establecimiento/ajax?cueanexo=220054600
    /**
     * @Route("/ajax", name="establecimiento_ajax", methods={"GET"})
     */
    public function establecimientosAjax(Request $request, EstablecimientoRepository $establecimientoRepository): JsonResponse
    {
        $cueanexo = $request->query->get('cueanexo');

        if (!is_numeric($cueanexo)) {
            return new JsonResponse([]);
        }

        $establecimientos = $establecimientoRepository->findLikeCueanexo($cueanexo);

        $establecimientosArray = [];
        foreach ($establecimientos as $establecimiento) {
            $establecimientosArray[] = [
                'id' => $establecimiento->getId(),
                'nombre' => $establecimiento->getNombre(),
                'cueanexo' => $establecimiento->getCueanexo(),
            ];
        }

        return new JsonResponse($establecimientosArray);
    }






    // /**
    //  * @Route("/ajax", name="establecimiento_ajax", methods={"GET"})
    //  */
    // public function establecimientosAjax(EstablecimientoRepository $establecimientoRepository): JsonResponse
    // {
    //     $establecimientos = $establecimientoRepository->findAll();

    //     // Convertir los datos de los establecimientos a un array de arrays asociativos
    //     $establecimientosArray = [];
    //     foreach ($establecimientos as $establecimiento) {
    //         $establecimientosArray[] = [
    //             'id' => $establecimiento->getId(),
    //             'nombre' => $establecimiento->getNombre(),
    //             'cueanexo' => $establecimiento->getCueanexo(),
    //             // Agregar más propiedades según sea necesario
    //         ];
    //     }

    //     // Devolver los datos en formato JSON
    //     return new JsonResponse($establecimientosArray);
    // }

    /**
     * @Route("/new", name="app_establecimiento_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EstablecimientoRepository $establecimientoRepository): Response
    {
        $establecimiento = new Establecimiento();
        $form = $this->createForm(EstablecimientoType::class, $establecimiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $establecimientoRepository->add($establecimiento, true);

            return $this->redirectToRoute('app_establecimiento_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('establecimiento/new.html.twig', [
            'establecimiento' => $establecimiento,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_establecimiento_show", methods={"GET"})
     */
    public function show(Establecimiento $establecimiento): Response
    {
        return $this->render('establecimiento/show.html.twig', [
            'establecimiento' => $establecimiento,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_establecimiento_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Establecimiento $establecimiento, EstablecimientoRepository $establecimientoRepository): Response
    {
        $form = $this->createForm(EstablecimientoType::class, $establecimiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $establecimientoRepository->add($establecimiento, true);

            return $this->redirectToRoute('app_establecimiento_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('establecimiento/edit.html.twig', [
            'establecimiento' => $establecimiento,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_establecimiento_delete", methods={"POST"})
     */
    public function delete(Request $request, Establecimiento $establecimiento, EstablecimientoRepository $establecimientoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$establecimiento->getId(), $request->request->get('_token'))) {
            $establecimientoRepository->remove($establecimiento, true);
        }

        return $this->redirectToRoute('app_establecimiento_index', [], Response::HTTP_SEE_OTHER);
    }
}
