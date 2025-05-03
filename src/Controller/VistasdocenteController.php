<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cursada;
use App\Repository\CursadaRepository;
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
use Psr\Log\LoggerInterface;
class VistasdocenteController extends AbstractController
{
    #[Route('/vistasdocente', name: 'app_vistasdocente')]
    #[Route('/nuevalista', name: 'app_nuevalista')]
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
    
    #[Route('/pasarlista/{curso_id}', name: 'app_pasarlista')]
    public function pasarlista(int $curso_id, CursoRepository $cursoRepository, CursadaRepository $cursadaRepository): Response
    {
        $curso = $cursoRepository->find($curso_id);
    
        if (!$curso) {
            throw $this->createNotFoundException('Curso no encontrado');
        }
    
        $cursadas = $cursadaRepository->findBy(['curso' => $curso]);
    
        return $this->render('vistasdocente/pasarlista.html.twig', [
            'curso' => $curso,
            'cursadas' => $cursadas,
            
        ]);
    }


    #[Route('/guardar-asistencia', name: 'guardar_asistencia', methods: ['POST'])]
    public function guardarAsistencia(Request $request, EntityManagerInterface $em, LoggerInterface $logger): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        $logger->info('Datos recibidos para guardar asistencia', ['data' => $data]);
    
        foreach ($data as $asistenciaData) {
            $logger->info('Procesando asistencia', ['asistenciaData' => $asistenciaData]);
    
            // Buscar la cursada correspondiente
            $cursada = $em->getRepository(Cursada::class)->find($asistenciaData['cursada_id']);
    
            if (!$cursada) {
                $logger->error('Cursada no encontrada', ['cursada_id' => $asistenciaData['cursada_id']]);
                continue;
            }
    
            // Comprobar si ya existe una asistencia para la misma cursada y fecha
            $fecha = new \DateTime($asistenciaData['fecha']);
            $asistenciaExistente = $em->getRepository(Asistencia::class)->findOneBy([
                'cursada' => $cursada,
                'fecha' => $fecha
            ]);
    
            if ($asistenciaExistente) {
                // Si ya existe, actualizamos la asistencia
                $logger->info('Actualizando asistencia existente', ['asistenciaExistente' => $asistenciaExistente]);
    
                $asistenciaExistente->setAsistencia($asistenciaData['estado']);
                $asistenciaExistente->setObservacion($asistenciaData['observacion']);
                
                $em->persist($asistenciaExistente);
            } else {
                // Si no existe, creamos una nueva asistencia
                $logger->info('Creando nueva asistencia', ['asistenciaData' => $asistenciaData]);
    
                $asistencia = new Asistencia();
                $asistencia->setCursada($cursada);
                $asistencia->setFecha($fecha);
                $asistencia->setAsistencia($asistenciaData['estado']);
                $asistencia->setObservacion($asistenciaData['observacion']);
    
                $em->persist($asistencia);
            }
        }
    
        $em->flush();
    
        return new JsonResponse(['status' => 'success'], 200);
    }
    
    #[Route('/actualizar-lista-alumnos/{curso_id}', name: 'actualizar_lista_alumnos', methods: ['GET'])]
    public function actualizarListaAlumnos(int $curso_id, CursoRepository $cursoRepository, CursadaRepository $cursadaRepository): JsonResponse
    {
        $curso = $cursoRepository->find($curso_id);
    
        if (!$curso) {
            return new JsonResponse(['error' => 'Curso no encontrado'], 404);
        }
    
        $cursadas = $cursadaRepository->findBy(['curso' => $curso]);
    
        $data = [];
        foreach ($cursadas as $cursada) {
            $alumno = $cursada->getAlumno();
            $ultimaAsistencia = $cursada->getAsistencias()->last();
    
            $data[] = [
                'id' => $cursada->getId(),
                'nombre' => $alumno->getNombre(),
                'apellido' => $alumno->getApellido(),
                'dni' => $alumno->getDniPasaporte(),
                'asistencia' => $ultimaAsistencia ? $ultimaAsistencia->getAsistencia() : 'No marcado',
                'observacion' => $ultimaAsistencia ? $ultimaAsistencia->getObservacion() : '',
            ];
        }
    
        return new JsonResponse($data);
    }

    #[Route('/templateprueba', name: 'app_prueba')]
    public function prueba(): Response
    {
        return $this->render('vistasdocente/templateprueba.html.twig');
    }

    
}


    

