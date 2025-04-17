<?php

namespace App\Controller;

use App\Entity\ExamenAlumno;
use App\Form\ExamenAlumnoType;
use App\Repository\ExamenAlumnoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/examen/alumno')]
class ExamenAlumnoController extends AbstractController
{
    #[Route('/', name: 'app_examen_alumno_index', methods: ['GET'])]
    public function index(ExamenAlumnoRepository $examenAlumnoRepository): Response
    {
        return $this->render('examen_alumno/index.html.twig', [
            'examen_alumnos' => $examenAlumnoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_examen_alumno_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ExamenAlumnoRepository $examenAlumnoRepository): Response
    {
        $examenAlumno = new ExamenAlumno();
        $form = $this->createForm(ExamenAlumnoType::class, $examenAlumno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $examenAlumnoRepository->save($examenAlumno, true);

            return $this->redirectToRoute('app_examen_alumno_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('examen_alumno/new.html.twig', [
            'examen_alumno' => $examenAlumno,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_examen_alumno_show', methods: ['GET'])]
    public function show(ExamenAlumno $examenAlumno): Response
    {
        return $this->render('examen_alumno/show.html.twig', [
            'examen_alumno' => $examenAlumno,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_examen_alumno_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ExamenAlumno $examenAlumno, ExamenAlumnoRepository $examenAlumnoRepository): Response
    {
        $form = $this->createForm(ExamenAlumnoType::class, $examenAlumno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $examenAlumnoRepository->save($examenAlumno, true);

            return $this->redirectToRoute('app_examen_alumno_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('examen_alumno/edit.html.twig', [
            'examen_alumno' => $examenAlumno,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_examen_alumno_delete', methods: ['POST'])]
    public function delete(Request $request, ExamenAlumno $examenAlumno, ExamenAlumnoRepository $examenAlumnoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenAlumno->getId(), $request->request->get('_token'))) {
            $examenAlumnoRepository->remove($examenAlumno, true);
        }

        return $this->redirectToRoute('app_examen_alumno_index', [], Response::HTTP_SEE_OTHER);
    }
}
