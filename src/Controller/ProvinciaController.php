<?php

namespace App\Controller;

use App\Entity\Provincia;
use App\Form\ProvinciaType;
use App\Repository\ProvinciaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/provincia')]
class ProvinciaController extends AbstractController
{
    #[Route('/', name: 'app_provincia_index', methods: ['GET'])]
    public function index(ProvinciaRepository $provinciaRepository): Response
    {
        return $this->render('provincia/index.html.twig', [
            'provincias' => $provinciaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_provincia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProvinciaRepository $provinciaRepository): Response
    {
        $provincium = new Provincia();
        $form = $this->createForm(ProvinciaType::class, $provincium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $provinciaRepository->save($provincium, true);

            return $this->redirectToRoute('app_provincia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('provincia/new.html.twig', [
            'provincium' => $provincium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_provincia_show', methods: ['GET'])]
    public function show(Provincia $provincium): Response
    {
        return $this->render('provincia/show.html.twig', [
            'provincium' => $provincium,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_provincia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Provincia $provincium, ProvinciaRepository $provinciaRepository): Response
    {
        $form = $this->createForm(ProvinciaType::class, $provincium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $provinciaRepository->save($provincium, true);

            return $this->redirectToRoute('app_provincia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('provincia/edit.html.twig', [
            'provincium' => $provincium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_provincia_delete', methods: ['POST'])]
    public function delete(Request $request, Provincia $provincium, ProvinciaRepository $provinciaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$provincium->getId(), $request->request->get('_token'))) {
            $provinciaRepository->remove($provincium, true);
        }

        return $this->redirectToRoute('app_provincia_index', [], Response::HTTP_SEE_OTHER);
    }
}
