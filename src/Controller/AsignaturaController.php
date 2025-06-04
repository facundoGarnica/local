<?php

namespace App\Controller;

use App\Entity\Asignatura;
use App\Form\AsignaturaType;
use App\Repository\AsignaturaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/asignatura')]
class AsignaturaController extends AbstractController
{
    #[Route('/', name: 'app_asignatura_index', methods: ['GET'])]
    public function index(AsignaturaRepository $asignaturaRepository): Response
    {
        return $this->render('asignatura/index.html.twig', [
            'asignaturas' => $asignaturaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_asignatura_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AsignaturaRepository $asignaturaRepository): Response
    {
        $asignatura = new Asignatura();
        $form = $this->createForm(AsignaturaType::class, $asignatura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $asignaturaRepository->save($asignatura, true);

            return $this->redirectToRoute('app_asignatura_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('asignatura/new.html.twig', [
            'asignatura' => $asignatura,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asignatura_show', methods: ['GET'])]
    public function show(Asignatura $asignatura): Response
    {
        return $this->render('asignatura/show.html.twig', [
            'asignatura' => $asignatura,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_asignatura_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Asignatura $asignatura, AsignaturaRepository $asignaturaRepository): Response
    {
        $form = $this->createForm(AsignaturaType::class, $asignatura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $asignaturaRepository->save($asignatura, true);

            return $this->redirectToRoute('app_asignatura_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('asignatura/edit.html.twig', [
            'asignatura' => $asignatura,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asignatura_delete', methods: ['POST'])]
    public function delete(Request $request, Asignatura $asignatura, AsignaturaRepository $asignaturaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$asignatura->getId(), $request->request->get('_token'))) {
            $asignaturaRepository->remove($asignatura, true);
        }

        return $this->redirectToRoute('app_asignatura_index', [], Response::HTTP_SEE_OTHER);
    }
}
