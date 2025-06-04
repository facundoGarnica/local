<?php

namespace App\Controller;

use App\Entity\Tecnicatura;
use App\Form\TecnicaturaType;
use App\Repository\TecnicaturaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tecnicatura')]
class TecnicaturaController extends AbstractController
{
    #[Route('/', name: 'app_tecnicatura_index', methods: ['GET'])]
    public function index(TecnicaturaRepository $tecnicaturaRepository): Response
    {
        return $this->render('tecnicatura/index.html.twig', [
            'tecnicaturas' => $tecnicaturaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tecnicatura_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TecnicaturaRepository $tecnicaturaRepository): Response
    {
        $tecnicatura = new Tecnicatura();
        $form = $this->createForm(TecnicaturaType::class, $tecnicatura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tecnicaturaRepository->save($tecnicatura, true);

            return $this->redirectToRoute('app_tecnicatura_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tecnicatura/new.html.twig', [
            'tecnicatura' => $tecnicatura,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tecnicatura_show', methods: ['GET'])]
    public function show(Tecnicatura $tecnicatura): Response
    {
        return $this->render('tecnicatura/show.html.twig', [
            'tecnicatura' => $tecnicatura,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tecnicatura_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tecnicatura $tecnicatura, TecnicaturaRepository $tecnicaturaRepository): Response
    {
        $form = $this->createForm(TecnicaturaType::class, $tecnicatura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tecnicaturaRepository->save($tecnicatura, true);

            return $this->redirectToRoute('app_tecnicatura_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tecnicatura/edit.html.twig', [
            'tecnicatura' => $tecnicatura,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tecnicatura_delete', methods: ['POST'])]
    public function delete(Request $request, Tecnicatura $tecnicatura, TecnicaturaRepository $tecnicaturaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tecnicatura->getId(), $request->request->get('_token'))) {
            $tecnicaturaRepository->remove($tecnicatura, true);
        }

        return $this->redirectToRoute('app_tecnicatura_index', [], Response::HTTP_SEE_OTHER);
    }
}
