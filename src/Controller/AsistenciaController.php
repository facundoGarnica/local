<?php

namespace App\Controller;

use App\Entity\Asistencia;
use App\Form\AsistenciaType;
use App\Repository\AsistenciaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/asistencia')]
class AsistenciaController extends AbstractController
{
    #[Route('/', name: 'app_asistencia_index', methods: ['GET'])]
    public function index(AsistenciaRepository $asistenciaRepository): Response
    {
        return $this->render('asistencia/index.html.twig', [
            'asistencias' => $asistenciaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_asistencia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AsistenciaRepository $asistenciaRepository): Response
    {
        $asistencium = new Asistencia();
        $form = $this->createForm(AsistenciaType::class, $asistencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $asistenciaRepository->save($asistencium, true);

            return $this->redirectToRoute('app_asistencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('asistencia/new.html.twig', [
            'asistencium' => $asistencium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asistencia_show', methods: ['GET'])]
    public function show(Asistencia $asistencium): Response
    {
        return $this->render('asistencia/show.html.twig', [
            'asistencium' => $asistencium,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_asistencia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Asistencia $asistencium, AsistenciaRepository $asistenciaRepository): Response
    {
        $form = $this->createForm(AsistenciaType::class, $asistencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $asistenciaRepository->save($asistencium, true);

            return $this->redirectToRoute('app_asistencia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('asistencia/edit.html.twig', [
            'asistencium' => $asistencium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asistencia_delete', methods: ['POST'])]
    public function delete(Request $request, Asistencia $asistencium, AsistenciaRepository $asistenciaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$asistencium->getId(), $request->request->get('_token'))) {
            $asistenciaRepository->remove($asistencium, true);
        }

        return $this->redirectToRoute('app_asistencia_index', [], Response::HTTP_SEE_OTHER);
    }
}
