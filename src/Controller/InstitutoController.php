<?php

namespace App\Controller;

use App\Entity\Instituto;
use App\Form\InstitutoType;
use App\Repository\InstitutoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/instituto')]
class InstitutoController extends AbstractController
{
    #[Route('/', name: 'app_instituto_index', methods: ['GET'])]
    public function index(InstitutoRepository $institutoRepository): Response
    {
        return $this->render('instituto/index.html.twig', [
            'institutos' => $institutoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_instituto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InstitutoRepository $institutoRepository): Response
    {
        $instituto = new Instituto();
        $form = $this->createForm(InstitutoType::class, $instituto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $institutoRepository->save($instituto, true);

            return $this->redirectToRoute('app_instituto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('instituto/new.html.twig', [
            'instituto' => $instituto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_instituto_show', methods: ['GET'])]
    public function show(Instituto $instituto): Response
    {
        return $this->render('instituto/show.html.twig', [
            'instituto' => $instituto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_instituto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Instituto $instituto, InstitutoRepository $institutoRepository): Response
    {
        $form = $this->createForm(InstitutoType::class, $instituto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $institutoRepository->save($instituto, true);

            return $this->redirectToRoute('app_instituto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('instituto/edit.html.twig', [
            'instituto' => $instituto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_instituto_delete', methods: ['POST'])]
    public function delete(Request $request, Instituto $instituto, InstitutoRepository $institutoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$instituto->getId(), $request->request->get('_token'))) {
            $institutoRepository->remove($instituto, true);
        }

        return $this->redirectToRoute('app_instituto_index', [], Response::HTTP_SEE_OTHER);
    }
}
