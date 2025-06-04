<?php

namespace App\Controller;

use App\Entity\Localidad;
use App\Form\LocalidadType;
use App\Repository\LocalidadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/localidad')]
class LocalidadController extends AbstractController
{
    #[Route('/', name: 'app_localidad_index', methods: ['GET'])]
    public function index(LocalidadRepository $localidadRepository): Response
    {
        return $this->render('localidad/index.html.twig', [
            'localidads' => $localidadRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_localidad_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LocalidadRepository $localidadRepository): Response
    {
        $localidad = new Localidad();
        $form = $this->createForm(LocalidadType::class, $localidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $localidadRepository->save($localidad, true);

            return $this->redirectToRoute('app_localidad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('localidad/new.html.twig', [
            'localidad' => $localidad,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localidad_show', methods: ['GET'])]
    public function show(Localidad $localidad): Response
    {
        return $this->render('localidad/show.html.twig', [
            'localidad' => $localidad,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_localidad_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Localidad $localidad, LocalidadRepository $localidadRepository): Response
    {
        $form = $this->createForm(LocalidadType::class, $localidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $localidadRepository->save($localidad, true);

            return $this->redirectToRoute('app_localidad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('localidad/edit.html.twig', [
            'localidad' => $localidad,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localidad_delete', methods: ['POST'])]
    public function delete(Request $request, Localidad $localidad, LocalidadRepository $localidadRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$localidad->getId(), $request->request->get('_token'))) {
            $localidadRepository->remove($localidad, true);
        }

        return $this->redirectToRoute('app_localidad_index', [], Response::HTTP_SEE_OTHER);
    }
}
