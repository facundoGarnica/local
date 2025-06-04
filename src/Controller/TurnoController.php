<?php

namespace App\Controller;

use App\Entity\Turno;
use App\Form\TurnoType;
use App\Repository\TurnoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/turno')]
class TurnoController extends AbstractController
{
    #[Route('/', name: 'app_turno_index', methods: ['GET'])]
    public function index(TurnoRepository $turnoRepository): Response
    {
        return $this->render('turno/index.html.twig', [
            'turnos' => $turnoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_turno_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TurnoRepository $turnoRepository): Response
    {
        $turno = new Turno();
        $form = $this->createForm(TurnoType::class, $turno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $turnoRepository->save($turno, true);

            return $this->redirectToRoute('app_turno_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('turno/new.html.twig', [
            'turno' => $turno,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_turno_show', methods: ['GET'])]
    public function show(Turno $turno): Response
    {
        return $this->render('turno/show.html.twig', [
            'turno' => $turno,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_turno_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Turno $turno, TurnoRepository $turnoRepository): Response
    {
        $form = $this->createForm(TurnoType::class, $turno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $turnoRepository->save($turno, true);

            return $this->redirectToRoute('app_turno_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('turno/edit.html.twig', [
            'turno' => $turno,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_turno_delete', methods: ['POST'])]
    public function delete(Request $request, Turno $turno, TurnoRepository $turnoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$turno->getId(), $request->request->get('_token'))) {
            $turnoRepository->remove($turno, true);
        }

        return $this->redirectToRoute('app_turno_index', [], Response::HTTP_SEE_OTHER);
    }
}
