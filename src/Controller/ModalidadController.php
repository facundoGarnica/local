<?php

namespace App\Controller;

use App\Entity\Modalidad;
use App\Form\ModalidadType;
use App\Repository\ModalidadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/modalidad')]
class ModalidadController extends AbstractController
{
    #[Route('/', name: 'app_modalidad_index', methods: ['GET'])]
    public function index(ModalidadRepository $modalidadRepository): Response
    {
        return $this->render('modalidad/index.html.twig', [
            'modalidads' => $modalidadRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_modalidad_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ModalidadRepository $modalidadRepository): Response
    {
        $modalidad = new Modalidad();
        $form = $this->createForm(ModalidadType::class, $modalidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modalidadRepository->save($modalidad, true);

            return $this->redirectToRoute('app_modalidad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('modalidad/new.html.twig', [
            'modalidad' => $modalidad,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_modalidad_show', methods: ['GET'])]
    public function show(Modalidad $modalidad): Response
    {
        return $this->render('modalidad/show.html.twig', [
            'modalidad' => $modalidad,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_modalidad_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Modalidad $modalidad, ModalidadRepository $modalidadRepository): Response
    {
        $form = $this->createForm(ModalidadType::class, $modalidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modalidadRepository->save($modalidad, true);

            return $this->redirectToRoute('app_modalidad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('modalidad/edit.html.twig', [
            'modalidad' => $modalidad,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_modalidad_delete', methods: ['POST'])]
    public function delete(Request $request, Modalidad $modalidad, ModalidadRepository $modalidadRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$modalidad->getId(), $request->request->get('_token'))) {
            $modalidadRepository->remove($modalidad, true);
        }

        return $this->redirectToRoute('app_modalidad_index', [], Response::HTTP_SEE_OTHER);
    }
}
