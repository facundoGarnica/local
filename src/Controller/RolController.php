<?php

namespace App\Controller;

use App\Entity\Rol;
use App\Form\RolType;
use App\Repository\RolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rol')]
class RolController extends AbstractController
{
    #[Route('/', name: 'app_rol_index', methods: ['GET'])]
    public function index(RolRepository $rolRepository): Response
    {
        return $this->render('rol/index.html.twig', [
            'rols' => $rolRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rol_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rol = new Rol();
        $form = $this->createForm(RolType::class, $rol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rol);
            $entityManager->flush();

            return $this->redirectToRoute('app_rol_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rol/new.html.twig', [
            'rol' => $rol,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rol_show', methods: ['GET'])]
    public function show(Rol $rol): Response
    {
        return $this->render('rol/show.html.twig', [
            'rol' => $rol,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rol_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rol $rol, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RolType::class, $rol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rol_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rol/edit.html.twig', [
            'rol' => $rol,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rol_delete', methods: ['POST'])]
    public function delete(Request $request, Rol $rol, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rol->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rol);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rol_index', [], Response::HTTP_SEE_OTHER);
    }
}
