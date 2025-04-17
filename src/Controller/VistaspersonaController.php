<?php

namespace App\Controller;

use App\Entity\Carreras;
use App\Form\CarrerasType;
use App\Repository\CarrerasRepository;
use App\Entity\Persona;
use App\Form\PersonaType;
use App\Repository\PersonaRepository;
use App\Entity\Alumno;
use App\Form\AlumnoType;
use App\Repository\AlumnoRepository;
use App\Entity\Docente;
use App\Form\DocenteType;
use App\Repository\DocenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VistaspersonaController extends AbstractController
{
    #[Route('/vistaspersona', name: 'app_vistaspersona')]
    public function index(CarrerasRepository $carrerasRepository, PersonaRepository $personaRepository, AlumnoRepository $alumnoRepository, DocenteRepository $docenteRepository, Request $request): Response
    {
        $session = $request->getSession();
        $alumnoId = $session->get('alumnoId', null);
        $docenteId = $session->get('docenteId', null);
        $personaId = $session->get('personaId', null);
        $carrerasId = $session->get('carrerasId', null);

        if ($alumnoId === null) {
            $alumnoId = null; 
        } else {
            $session->remove('alumnoId');
        }

        if ($docenteId === null) {
            $docenteId = null; 
        } else {
            $session->remove('docenteId');
        }

        
        if ($personaId === null) {
            $personaId = null; 
        } else {
            $session->remove('personaId');
        }

        if ($carrerasId === null) {
            $carrerasId = null; 
        } else {
            $session->remove('carrerasId');
        }

        return $this->render('vistaspersona/index.html.twig', [
            'personas' => $personaRepository->findAll(),
            'alumnos' => $alumnoRepository->findAll(),
            'docentes' => $docenteRepository->findAll(),
            'carreras' => $carrerasRepository->findAll(),
            'alumnoId' => $alumnoId,
            'docenteId' => $docenteId,
            'personaId' => $personaId,
            'carrerasId' => $carrerasId,
        ]);
    }

    #[Route('/nuevapersona', name: 'crear_persona', methods: ['GET', 'POST'])]
    public function nuevapersona(Request $request, PersonaRepository $personaRepository): Response
    {
        $persona = new Persona();
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personaRepository->save($persona, true);

            return $this->redirectToRoute('app_vistaspersona', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vistaspersona/create_persona_form.html.twig', [
            'persona' => $persona,
            'form' => $form,
        ]);
    }

    #[Route('/nuevodocente', name: 'crear_docente', methods: ['GET', 'POST'])]
    public function nuevodocente(Request $request, DocenteRepository $docenteRepository): Response
    {
        $docente = new Docente();
        $form = $this->createForm(DocenteType::class, $docente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $docenteRepository->save($docente, true);

            return $this->redirectToRoute('app_vistaspersona', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vistaspersona/create_docente_form.html.twig', [
            'docente' => $docente,
            'form' => $form,
        ]);
    }

    #[Route('/nuevoalumno', name: 'crear_alumno', methods: ['GET', 'POST'])]
    public function nuevoalumno(Request $request, AlumnoRepository $alumnoRepository): Response
    {
        $alumno = new Alumno();
        $form = $this->createForm(AlumnoType::class, $alumno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alumnoRepository->save($alumno, true);

            return $this->redirectToRoute('app_vistaspersona', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vistaspersona/create_alumno_form.html.twig', [
            'alumno' => $alumno,
            'form' => $form,
        ]);
    }

    #[Route('/nuevacarreras', name: 'crear_carreras', methods: ['GET', 'POST'])]
    public function nuevacarreras(Request $request, CarrerasRepository $carrerasRepository): Response
    {
        $carreras = new Carreras();
        $form = $this->createForm(CarrerasType::class, $carreras);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carrerasRepository->save($carreras, true);

            return $this->redirectToRoute('app_vistaspersona', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vistaspersona/create_carreras_form.html.twig', [
            'carreras' => $carreras,
            'form' => $form,
        ]);
    }


    #[Route('/editaralumno/{id}', name: 'editar_alumno', methods: ['GET', 'POST'])]
    public function editarAlumno(Request $request, Alumno $alumno, AlumnoRepository $alumnoRepository, int $id): Response
    {
        // Recuperar `cursoId` de la sesión
        $session = $request->getSession();
        if ($id !== null) {
            $session->set('alumnoId', $id);
        }
    
        $alumnoId = $session->get('alumnoId');
    
        // Encontrar la nota utilizando el ID
        $alumno = $alumnoRepository->find($alumno->getId());
    
        // Crear el formulario
        $form = $this->createForm(AlumnoType::class, $alumno);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $alumnoRepository->save($alumno, true);

            return $this->redirectToRoute('app_vistaspersona', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('vistaspersona/edit_alumno.html.twig', [
            'alumno' => $alumno,
            'form' => $form,
            'alumnoId' => $alumnoId,
        ]);
    }

    #[Route('/editardocente/{id}', name: 'editar_docente', methods: ['GET', 'POST'])]
    public function editarDocente(Request $request, Docente $docente, DocenteRepository $docenteRepository, int $id): Response
    {
        // Recuperar `cursoId` de la sesión
        $session = $request->getSession();
        if ($id !== null) {
            $session->set('docenteId', $id);
        }
    
        $docenteId = $session->get('docenteId');
    
        // Encontrar la nota utilizando el ID
        $docente = $docenteRepository->find($docente->getId());
    
        // Crear el formulario
        $form = $this->createForm(DocenteType::class, $docente);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $docenteRepository->save($docente, true);

            return $this->redirectToRoute('app_vistaspersona', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('vistaspersona/edit_docente.html.twig', [
            'docente' => $docente,
            'form' => $form,
            'docenteId' => $docenteId,
        ]);
    }

    #[Route('/editarpersona/{id}', name: 'editar_persona', methods: ['GET', 'POST'])]
    public function editarPersona(Request $request, Persona $persona, PersonaRepository $personaRepository, int $id): Response
    {
        
        $session = $request->getSession();
        if ($id !== null) {
            $session->set('personaId', $id);
        }
    
        $personaId = $session->get('personaId');
    
        // Encontrar la nota utilizando el ID
        $persona = $personaRepository->find($persona->getId());
    
        // Crear el formulario
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $personaRepository->save($persona, true);

            return $this->redirectToRoute('app_vistaspersona', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('vistaspersona/edit_persona.html.twig', [
            'persona' => $persona,
            'form' => $form,
            'personaId' => $personaId,
        ]);
    }

    #[Route('/editarcarreras/{id}', name: 'editar_carreras', methods: ['GET', 'POST'])]
    public function editarCarreras(Request $request, Carreras $carreras, CarrerasRepository $carrerasRepository, int $id): Response
    {
        // Recuperar `cursoId` de la sesión
        $session = $request->getSession();
        if ($id !== null) {
            $session->set('carrerasId', $id);
        }
    
        $carrerasId = $session->get('carrerasId');
    
        // Encontrar la nota utilizando el ID
        $carreras = $carrerasRepository->find($carreras->getId());
    
        // Crear el formulario
        $form = $this->createForm(CarrerasType::class, $carreras);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $carrerasRepository->save($carreras, true);

            return $this->redirectToRoute('app_vistaspersona', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('vistaspersona/edit_carreras.html.twig', [
            'carreras' => $carreras,
            'form' => $form,
            'carrerasId' => $carrerasId,
        ]);
    }


}  
