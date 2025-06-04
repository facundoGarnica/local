<?php

namespace App\Controller;

use App\Entity\Telefono;
use App\Form\TelefonoType;
use App\Repository\TelefonoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/telefono')]
class TelefonoController extends AbstractController
{
    #[Route('/', name: 'app_telefono_index', methods: ['GET'])]
    public function index(TelefonoRepository $telefonoRepository): Response
    {
        return $this->render('telefono/index.html.twig', [
            'telefonos' => $telefonoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_telefono_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TelefonoRepository $telefonoRepository): Response
    {
        $telefono = new Telefono();
        $form = $this->createForm(TelefonoType::class, $telefono);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $telefonoRepository->save($telefono, true);

            return $this->redirectToRoute('app_telefono_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('telefono/new.html.twig', [
            'telefono' => $telefono,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_telefono_show', methods: ['GET'])]
    public function show(Telefono $telefono): Response
    {
        return $this->render('telefono/show.html.twig', [
            'telefono' => $telefono,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_telefono_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Telefono $telefono, TelefonoRepository $telefonoRepository): Response
    {
        $form = $this->createForm(TelefonoType::class, $telefono);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $telefonoRepository->save($telefono, true);

            return $this->redirectToRoute('app_telefono_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('telefono/edit.html.twig', [
            'telefono' => $telefono,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_telefono_delete', methods: ['POST'])]
    public function delete(Request $request, Telefono $telefono, TelefonoRepository $telefonoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$telefono->getId(), $request->request->get('_token'))) {
            $telefonoRepository->remove($telefono, true);
        }

        return $this->redirectToRoute('app_telefono_index', [], Response::HTTP_SEE_OTHER);
    }
}
