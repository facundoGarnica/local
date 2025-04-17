<?php

namespace App\Controller;

use App\Entity\CursadaDocente;
use App\Form\CursadaDocenteType;
use App\Repository\CursadaDocenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cursadadocente')]
class CursadaDocenteController extends AbstractController
{
    #[Route('/', name: 'app_cursada_docente_index', methods: ['GET'])]
    public function index(CursadaDocenteRepository $cursadaDocenteRepository): Response
    {
        return $this->render('cursada_docente/index.html.twig', [
            'cursada_docentes' => $cursadaDocenteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cursada_docente_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CursadaDocenteRepository $cursadaDocenteRepository): Response
    {
        $cursadaDocente = new CursadaDocente();
        $form = $this->createForm(CursadaDocenteType::class, $cursadaDocente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cursadaDocenteRepository->save($cursadaDocente, true);

            return $this->redirectToRoute('app_cursada_docente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cursada_docente/new.html.twig', [
            'cursada_docente' => $cursadaDocente,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cursada_docente_show', methods: ['GET'])]
    public function show(CursadaDocente $cursadaDocente): Response
    {
        return $this->render('cursada_docente/show.html.twig', [
            'cursada_docente' => $cursadaDocente,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cursada_docente_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CursadaDocente $cursadaDocente, CursadaDocenteRepository $cursadaDocenteRepository): Response
    {
        $form = $this->createForm(CursadaDocenteType::class, $cursadaDocente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cursadaDocenteRepository->save($cursadaDocente, true);

            return $this->redirectToRoute('app_cursada_docente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cursada_docente/edit.html.twig', [
            'cursada_docente' => $cursadaDocente,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cursada_docente_delete', methods: ['POST'])]
    public function delete(Request $request, CursadaDocente $cursadaDocente, CursadaDocenteRepository $cursadaDocenteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cursadaDocente->getId(), $request->request->get('_token'))) {
            $cursadaDocenteRepository->remove($cursadaDocente, true);
        }

        return $this->redirectToRoute('app_cursada_docente_index', [], Response::HTTP_SEE_OTHER);
    }
}
