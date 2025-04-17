<?php

namespace App\Controller;

use App\Entity\Comision;
use App\Form\ComisionType;
use App\Repository\ComisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comision')]
class ComisionController extends AbstractController
{
    #[Route('/', name: 'app_comision_index', methods: ['GET'])]
    public function index(ComisionRepository $comisionRepository): Response
    {
        return $this->render('comision/index.html.twig', [
            'comisions' => $comisionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_comision_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ComisionRepository $comisionRepository): Response
    {
        $comision = new Comision();
        $form = $this->createForm(ComisionType::class, $comision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comisionRepository->save($comision, true);

            return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comision/new.html.twig', [
            'comision' => $comision,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comision_show', methods: ['GET'])]
    public function show(Comision $comision): Response
    {
        return $this->render('comision/show.html.twig', [
            'comision' => $comision,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comision_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comision $comision, ComisionRepository $comisionRepository): Response
    {
        $form = $this->createForm(ComisionType::class, $comision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comisionRepository->save($comision, true);

            return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comision/edit.html.twig', [
            'comision' => $comision,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comision_delete', methods: ['POST'])]
    public function delete(Request $request, Comision $comision, ComisionRepository $comisionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comision->getId(), $request->request->get('_token'))) {
            $comisionRepository->remove($comision, true);
        }

        return $this->redirectToRoute('app_comision_index', [], Response::HTTP_SEE_OTHER);
    }
}
