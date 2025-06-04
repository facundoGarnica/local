<?php

namespace App\Controller;

use App\Entity\Nota;
use App\Form\NotaType;
use App\Repository\NotaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/nota')]
class NotaController extends AbstractController
{
    #[Route('/', name: 'app_nota_index', methods: ['GET'])]
    public function index(NotaRepository $notaRepository): Response
    {
        return $this->render('nota/index.html.twig', [
            'notas' => $notaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_nota_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NotaRepository $notaRepository): Response
    {
        $notum = new Nota();
        $form = $this->createForm(NotaType::class, $notum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notaRepository->save($notum, true);

            return $this->redirectToRoute('app_nota_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nota/new.html.twig', [
            'notum' => $notum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nota_show', methods: ['GET'])]
    public function show(Nota $notum): Response
    {
        return $this->render('nota/show.html.twig', [
            'notum' => $notum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nota_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nota $notum, NotaRepository $notaRepository): Response
    {
        $form = $this->createForm(NotaType::class, $notum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notaRepository->save($notum, true);

            return $this->redirectToRoute('app_nota_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nota/edit.html.twig', [
            'notum' => $notum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nota_delete', methods: ['POST'])]
    public function delete(Request $request, Nota $notum, NotaRepository $notaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notum->getId(), $request->request->get('_token'))) {
            $notaRepository->remove($notum, true);
        }

        return $this->redirectToRoute('app_nota_index', [], Response::HTTP_SEE_OTHER);
    }
}
