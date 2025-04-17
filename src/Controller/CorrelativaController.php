<?php

namespace App\Controller;

use App\Entity\Correlativa;
use App\Form\CorrelativaType;
use App\Repository\CorrelativaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/correlativa')]
class CorrelativaController extends AbstractController
{
    #[Route('/', name: 'app_correlativa_index', methods: ['GET'])]
    public function index(CorrelativaRepository $correlativaRepository): Response
    {
        return $this->render('correlativa/index.html.twig', [
            'correlativas' => $correlativaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_correlativa_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CorrelativaRepository $correlativaRepository): Response
    {
        $correlativa = new Correlativa();
        $form = $this->createForm(CorrelativaType::class, $correlativa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $correlativaRepository->save($correlativa, true);

            return $this->redirectToRoute('app_correlativa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('correlativa/new.html.twig', [
            'correlativa' => $correlativa,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_correlativa_show', methods: ['GET'])]
    public function show(Correlativa $correlativa): Response
    {
        return $this->render('correlativa/show.html.twig', [
            'correlativa' => $correlativa,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_correlativa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Correlativa $correlativa, CorrelativaRepository $correlativaRepository): Response
    {
        $form = $this->createForm(CorrelativaType::class, $correlativa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $correlativaRepository->save($correlativa, true);

            return $this->redirectToRoute('app_correlativa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('correlativa/edit.html.twig', [
            'correlativa' => $correlativa,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_correlativa_delete', methods: ['POST'])]
    public function delete(Request $request, Correlativa $correlativa, CorrelativaRepository $correlativaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$correlativa->getId(), $request->request->get('_token'))) {
            $correlativaRepository->remove($correlativa, true);
        }

        return $this->redirectToRoute('app_correlativa_index', [], Response::HTTP_SEE_OTHER);
    }
}
