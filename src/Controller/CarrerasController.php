<?php

namespace App\Controller;

use App\Entity\Carreras;
use App\Form\CarrerasType;
use App\Repository\CarrerasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/carreras')]
class CarrerasController extends AbstractController
{
    #[Route('/', name: 'app_carreras_index', methods: ['GET'])]
    public function index(CarrerasRepository $carrerasRepository): Response
    {
        return $this->render('carreras/index.html.twig', [
            'carreras' => $carrerasRepository->findAll(),
        ]); 
    }

    #[Route('/new', name: 'app_carreras_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CarrerasRepository $carrerasRepository): Response
    {
        $carrera = new Carreras();
        $form = $this->createForm(CarrerasType::class, $carrera);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carrerasRepository->save($carrera, true);

            return $this->redirectToRoute('app_carreras_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carreras/new.html.twig', [
            'carrera' => $carrera,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carreras_show', methods: ['GET'])]
    public function show(Carreras $carrera): Response
    {
        return $this->render('carreras/show.html.twig', [
            'carrera' => $carrera,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_carreras_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Carreras $carrera, CarrerasRepository $carrerasRepository): Response
    {
        $form = $this->createForm(CarrerasType::class, $carrera);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carrerasRepository->save($carrera, true);

            return $this->redirectToRoute('app_carreras_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carreras/edit.html.twig', [
            'carrera' => $carrera,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carreras_delete', methods: ['POST'])]
    public function delete(Request $request, Carreras $carrera, CarrerasRepository $carrerasRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carrera->getId(), $request->request->get('_token'))) {
            $carrerasRepository->remove($carrera, true);
        }

        return $this->redirectToRoute('app_carreras_index', [], Response::HTTP_SEE_OTHER);
    }
}
