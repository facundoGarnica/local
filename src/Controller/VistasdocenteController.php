<?php

namespace App\Controller;

use App\Entity\Asistencia;
use App\Form\AsistenciaType;
use App\Repository\AsistenciaRepository;
use App\Entity\Nota;
use App\Form\NotaType;
use App\Repository\NotaRepository;
use App\Entity\Curso;
use App\Form\CursoType;
use App\Repository\CursoRepository;
use App\Entity\CursadaDocente;
use App\Form\CursadaDocenteType;
use App\Repository\CursadaDocenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class VistasdocenteController extends AbstractController
{
    #[Route('/vistasdocente', name: 'app_vistasdocente')]
    public function index(NotaRepository $notaRepository, CursadaDocenteRepository $cursadaDocenteRepository, CursoRepository $cursoRepository, Request $request): Response
    {
        // Recuperar `cursoId` de la sesión esto para cuando estoy cargando asignaturas y que no se pierda cursoId
        $session = $request->getSession();
        $cursoId = $session->get('cursoId', null);
        $cursoId2 = $session->get('cursoId2', null);     
        if ($cursoId === null) {
            $cursoId = null; 
        } else {
            $session->remove('cursoId');
        }
        if ($cursoId2 !== null) {
            $session->remove('cursoId2'); 
        }

        $today = null;
    
        return $this->render('vistasdocente/index.html.twig', [
            'cursos' => $cursoRepository->findAll(),
            'cursada_docentes' => $cursadaDocenteRepository->findAll(),
            'cursoId' => $cursoId,
            'cursoId2' => $cursoId2,
            'today' => $today,
        ]);
    }

    #[Route('/editarnota/{id}/cursodesesion/{curso_id}', name: 'editar_nota', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nota $nota, NotaRepository $notaRepository, int $curso_id): Response
    {
        // Recuperar `cursoId` de la sesión
        $session = $request->getSession();
        if ($curso_id !== null) {
            $session->set('cursoId', $curso_id);
        }
    
        $cursoId = $session->get('cursoId');
    
        // Encontrar la nota utilizando el ID
        $nota = $notaRepository->find($nota->getId());
    
        // Crear el formulario
        $form = $this->createForm(NotaType::class, $nota);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $notaRepository->save($nota, true);
    
            return $this->redirectToRoute('app_vistasdocente', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('vistasdocente/edit_nota.html.twig', [
            'nota' => $nota,
            'form' => $form,
            'cursoId' => $cursoId,
        ]);
    }






    #[Route('/crearasistencia/{curso_id}', name: 'nuevas_asistencias', methods: ['GET', 'POST'])]
    public function nuevaAsistencia(Request $request, AsistenciaRepository $asistenciaRepository, int $curso_id): Response
    {

        // Recuperar `cursoId` de la sesión
        $session = $request->getSession();
            if ($curso_id !== null) {
            $session->set('cursoId2', $curso_id);
        }    
        $cursoId = $session->get('cursoId2');    

        $asistencium = new Asistencia();
        $form = $this->createForm(AsistenciaType::class, $asistencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $asistenciaRepository->save($asistencium, true);

            return $this->redirectToRoute('app_vistasdocente', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vistasdocente/crear_asistencias.html.twig', [
            'asistencium' => $asistencium,
            'form' => $form,
            'cursoId2' => $cursoId,
        ]);
    }


    #[Route('/{id}/edit/curso/{curso_id}', name: 'editar_asistencias', methods: ['GET', 'POST'])]
    public function editarAsistencia(Request $request, Asistencia $asistencium, AsistenciaRepository $asistenciaRepository, int $curso_id): Response
    {

        // Recuperar `cursoId` de la sesión
        $session = $request->getSession();
        if ($curso_id !== null) {
            $session->set('cursoId2', $curso_id);
        }    
        $cursoId = $session->get('cursoId2');     


        $form = $this->createForm(AsistenciaType::class, $asistencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $asistenciaRepository->save($asistencium, true);

            return $this->redirectToRoute('app_vistasdocente', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vistasdocente/editar_asistencias.html.twig', [
            'asistencium' => $asistencium,
            'form' => $form,
            'cursoId2' => $cursoId,
        ]);
    }

    #[Route('/nuevalista', name: 'app_nuevalista')]
    public function listaNueva(): Response
    {
        return $this->render('vistasdocente/nuevalista.html.twig');
    }
    
}



