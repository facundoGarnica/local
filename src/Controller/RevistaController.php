<?php

namespace App\Controller;

use App\Entity\Revista;
use App\Form\RevistaType;
use App\Repository\RevistaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/revista')]
class RevistaController extends AbstractController
{
    #[Route('/', name: 'app_revista_index', methods: ['GET'])]
    public function index(RevistaRepository $revistaRepository): Response
    {
        return $this->render('revista/index.html.twig', [
            'revistas' => $revistaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_revista_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RevistaRepository $revistaRepository): Response
    {
        $revistum = new Revista();
        $form = $this->createForm(RevistaType::class, $revistum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $revistaRepository->save($revistum, true);

            return $this->redirectToRoute('app_revista_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('revista/new.html.twig', [
            'revistum' => $revistum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_revista_show', methods: ['GET'])]
    public function show(Revista $revistum): Response
    {
        return $this->render('revista/show.html.twig', [
            'revistum' => $revistum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_revista_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Revista $revistum, RevistaRepository $revistaRepository): Response
    {
        $form = $this->createForm(RevistaType::class, $revistum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $revistaRepository->save($revistum, true);

            return $this->redirectToRoute('app_revista_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('revista/edit.html.twig', [
            'revistum' => $revistum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_revista_delete', methods: ['POST'])]
    public function delete(Request $request, Revista $revistum, RevistaRepository $revistaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$revistum->getId(), $request->request->get('_token'))) {
            $revistaRepository->remove($revistum, true);
        }

        return $this->redirectToRoute('app_revista_index', [], Response::HTTP_SEE_OTHER);
    }
}
