<?php

namespace App\Controller;

use App\Entity\InscripcionFinal;
use App\Form\InscripcionFinalType;
use App\Repository\InscripcionFinalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/inscripcion/final')]
class InscripcionFinalController extends AbstractController
{
    #[Route('/', name: 'app_inscripcion_final_index', methods: ['GET'])]
    public function index(InscripcionFinalRepository $inscripcionFinalRepository): Response
    {
        return $this->render('inscripcion_final/index.html.twig', [
            'inscripcion_finals' => $inscripcionFinalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_inscripcion_final_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InscripcionFinalRepository $inscripcionFinalRepository): Response
    {
        $inscripcionFinal = new InscripcionFinal();
        $form = $this->createForm(InscripcionFinalType::class, $inscripcionFinal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscripcionFinalRepository->save($inscripcionFinal, true);

            return $this->redirectToRoute('app_inscripcion_final_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inscripcion_final/new.html.twig', [
            'inscripcion_final' => $inscripcionFinal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_inscripcion_final_show', methods: ['GET'])]
    public function show(InscripcionFinal $inscripcionFinal): Response
    {
        return $this->render('inscripcion_final/show.html.twig', [
            'inscripcion_final' => $inscripcionFinal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_inscripcion_final_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InscripcionFinal $inscripcionFinal, InscripcionFinalRepository $inscripcionFinalRepository): Response
    {
        $form = $this->createForm(InscripcionFinalType::class, $inscripcionFinal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscripcionFinalRepository->save($inscripcionFinal, true);

            return $this->redirectToRoute('app_inscripcion_final_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inscripcion_final/edit.html.twig', [
            'inscripcion_final' => $inscripcionFinal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_inscripcion_final_delete', methods: ['POST'])]
    public function delete(Request $request, InscripcionFinal $inscripcionFinal, InscripcionFinalRepository $inscripcionFinalRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inscripcionFinal->getId(), $request->request->get('_token'))) {
            $inscripcionFinalRepository->remove($inscripcionFinal, true);
        }

        return $this->redirectToRoute('app_inscripcion_final_index', [], Response::HTTP_SEE_OTHER);
    }
}
