<?php

namespace App\Controller;

use App\Entity\Titulo;
use App\Form\TituloType;
use App\Repository\TituloRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/titulo')]
class TituloController extends AbstractController
{
    #[Route('/', name: 'app_titulo_index', methods: ['GET'])]
    public function index(TituloRepository $tituloRepository): Response
    {
        return $this->render('titulo/index.html.twig', [
            'titulos' => $tituloRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_titulo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TituloRepository $tituloRepository): Response
    {
        $titulo = new Titulo();
        $form = $this->createForm(TituloType::class, $titulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tituloRepository->save($titulo, true);

            return $this->redirectToRoute('app_titulo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('titulo/new.html.twig', [
            'titulo' => $titulo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_titulo_show', methods: ['GET'])]
    public function show(Titulo $titulo): Response
    {
        return $this->render('titulo/show.html.twig', [
            'titulo' => $titulo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_titulo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Titulo $titulo, TituloRepository $tituloRepository): Response
    {
        $form = $this->createForm(TituloType::class, $titulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tituloRepository->save($titulo, true);

            return $this->redirectToRoute('app_titulo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('titulo/edit.html.twig', [
            'titulo' => $titulo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_titulo_delete', methods: ['POST'])]
    public function delete(Request $request, Titulo $titulo, TituloRepository $tituloRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$titulo->getId(), $request->request->get('_token'))) {
            $tituloRepository->remove($titulo, true);
        }

        return $this->redirectToRoute('app_titulo_index', [], Response::HTTP_SEE_OTHER);
    }
}
