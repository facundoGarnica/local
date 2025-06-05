<?php

namespace App\Controller;

use App\Entity\CalendarioClase;
use App\Form\CalendarioClaseType;
use App\Repository\CalendarioClaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/calendario/clase')]
class CalendarioClaseController extends AbstractController
{
    #[Route('/', name: 'app_calendario_clase_index', methods: ['GET'])]
    public function index(CalendarioClaseRepository $calendarioClaseRepository): Response
    {
        return $this->render('calendario_clase/index.html.twig', [
            'calendario_clases' => $calendarioClaseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_calendario_clase_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $calendarioClase = new CalendarioClase();
        $form = $this->createForm(CalendarioClaseType::class, $calendarioClase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($calendarioClase);
            $entityManager->flush();

            return $this->redirectToRoute('app_calendario_clase_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendario_clase/new.html.twig', [
            'calendario_clase' => $calendarioClase,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_calendario_clase_show', methods: ['GET'])]
    public function show(CalendarioClase $calendarioClase): Response
    {
        return $this->render('calendario_clase/show.html.twig', [
            'calendario_clase' => $calendarioClase,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_calendario_clase_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CalendarioClase $calendarioClase, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CalendarioClaseType::class, $calendarioClase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_calendario_clase_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendario_clase/edit.html.twig', [
            'calendario_clase' => $calendarioClase,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_calendario_clase_delete', methods: ['POST'])]
    public function delete(Request $request, CalendarioClase $calendarioClase, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendarioClase->getId(), $request->request->get('_token'))) {
            $entityManager->remove($calendarioClase);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_calendario_clase_index', [], Response::HTTP_SEE_OTHER);
    }
}
