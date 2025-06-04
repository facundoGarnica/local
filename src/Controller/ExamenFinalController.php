<?php

namespace App\Controller;

use App\Entity\ExamenFinal;
use App\Form\ExamenFinalType;
use App\Repository\ExamenFinalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/examen/final')]
class ExamenFinalController extends AbstractController
{
    #[Route('/', name: 'app_examen_final_index', methods: ['GET'])]
    public function index(ExamenFinalRepository $examenFinalRepository): Response
    {
        return $this->render('examen_final/index.html.twig', [
            'examen_finals' => $examenFinalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_examen_final_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ExamenFinalRepository $examenFinalRepository): Response
    {
        $examenFinal = new ExamenFinal();
        $form = $this->createForm(ExamenFinalType::class, $examenFinal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $examenFinalRepository->save($examenFinal, true);

            return $this->redirectToRoute('app_examen_final_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('examen_final/new.html.twig', [
            'examen_final' => $examenFinal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_examen_final_show', methods: ['GET'])]
    public function show(ExamenFinal $examenFinal): Response
    {
        return $this->render('examen_final/show.html.twig', [
            'examen_final' => $examenFinal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_examen_final_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ExamenFinal $examenFinal, ExamenFinalRepository $examenFinalRepository): Response
    {
        $form = $this->createForm(ExamenFinalType::class, $examenFinal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $examenFinalRepository->save($examenFinal, true);

            return $this->redirectToRoute('app_examen_final_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('examen_final/edit.html.twig', [
            'examen_final' => $examenFinal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_examen_final_delete', methods: ['POST'])]
    public function delete(Request $request, ExamenFinal $examenFinal, ExamenFinalRepository $examenFinalRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenFinal->getId(), $request->request->get('_token'))) {
            $examenFinalRepository->remove($examenFinal, true);
        }

        return $this->redirectToRoute('app_examen_final_index', [], Response::HTTP_SEE_OTHER);
    }
}
