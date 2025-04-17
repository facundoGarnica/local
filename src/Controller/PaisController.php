<?php

namespace App\Controller;

use App\Entity\Pais;
use App\Form\PaisType;
use App\Repository\PaisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pais')]
class PaisController extends AbstractController
{
    #[Route('/', name: 'app_pais_index', methods: ['GET'])]
    public function index(PaisRepository $paisRepository): Response
    {
        return $this->render('pais/index.html.twig', [
            'pais' => $paisRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pais_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaisRepository $paisRepository): Response
    {
        $pai = new Pais();
        $form = $this->createForm(PaisType::class, $pai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paisRepository->save($pai, true);

            return $this->redirectToRoute('app_pais_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pais/new.html.twig', [
            'pai' => $pai,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pais_show', methods: ['GET'])]
    public function show(Pais $pai): Response
    {
        return $this->render('pais/show.html.twig', [
            'pai' => $pai,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pais_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pais $pai, PaisRepository $paisRepository): Response
    {
        $form = $this->createForm(PaisType::class, $pai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paisRepository->save($pai, true);

            return $this->redirectToRoute('app_pais_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pais/edit.html.twig', [
            'pai' => $pai,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pais_delete', methods: ['POST'])]
    public function delete(Request $request, Pais $pai, PaisRepository $paisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pai->getId(), $request->request->get('_token'))) {
            $paisRepository->remove($pai, true);
        }

        return $this->redirectToRoute('app_pais_index', [], Response::HTTP_SEE_OTHER);
    }
}
