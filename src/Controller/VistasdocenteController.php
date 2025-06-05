<?php

namespace App\Controller;

use App\Entity\CalendarioClase;
use App\Form\CalendarioClaseType;
use App\Repository\CalendarioClaseRepository;
use App\Form\CalendarioClaseCustomType;
use App\Repository\ModalidadRepository;
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
public function guardarAsistencia(Request $request, EntityManagerInterface $em)
{
    $datos = json_decode($request->getContent(), true);

    if (!is_array($datos)) {
        return new JsonResponse(['error' => 'Datos inválidos'], 400);
    }

    foreach ($datos as $asistenciaData) {
        if (
            empty($asistenciaData['cursada_id']) ||
            empty($asistenciaData['calendarioClase_id']) ||
            !isset($asistenciaData['estado'])
        ) {
            return new JsonResponse(['error' => 'Faltan datos obligatorios'], 400);
        }

        // Buscar la entidad Cursada y CalendarioClase
        $cursada = $em->getRepository(Cursada::class)->find($asistenciaData['cursada_id']);
        $calendarioClase = $em->getRepository(CalendarioClase::class)->find($asistenciaData['calendarioClase_id']);

        if (!$cursada || !$calendarioClase) {
            return new JsonResponse(['error' => 'Cursada o CalendarioClase no encontrados'], 404);
        }

        // Buscar si ya existe una asistencia para esa cursada y calendarioClase
        $asistencia = $em->getRepository(Asistencia::class)->findOneBy([
            'cursada' => $cursada,
            'calendarioClase' => $calendarioClase,
        ]);

        if (!$asistencia) {
            $asistencia = new Asistencia();
            $asistencia->setCursada($cursada);
            $asistencia->setCalendarioClase($calendarioClase);
        }

        // Actualizar datos
        $asistencia->setAsistencia($asistenciaData['estado']);
        $asistencia->setObservacion($asistenciaData['observacion'] ?? null);

        $em->persist($asistencia);
    }

    $em->flush();

    return new JsonResponse(['mensaje' => 'Asistencias guardadas correctamente']);
}


        
    #[Route('/actualizar-lista-alumnos/{curso_id}', name: 'actualizar_lista_alumnos', methods: ['GET'])]
    public function actualizarListaAlumnos(
        int $curso_id,
        CursoRepository $cursoRepository,
        CursadaRepository $cursadaRepository
    ): JsonResponse {
        // Establecer la zona horaria de Buenos Aires para evitar desfases
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $curso = $cursoRepository->find($curso_id);

        if (!$curso) {
            return new JsonResponse(['error' => 'Curso no encontrado'], 404);
        }

        $cursadas = $cursadaRepository->findBy(['curso' => $curso]);

        $data = [];
        $hoy = new \DateTime();
        $hoyFormato = $hoy->format('Y-m-d');

        foreach ($cursadas as $cursada) {
            $alumno = $cursada->getAlumno();
            $asistencias = $cursada->getAsistencias();

            $asistenciaHoy = null;

            foreach ($asistencias as $a) {
                if ($a->getFecha()->format('Y-m-d') === $hoyFormato) {
                    $asistenciaHoy = $a;
                    break;
                }
            }

            // Estadísticas
            $presentes = 0;
            $ausentes = 0;
            $mediaFaltas = 0;
            $justificadas = 0;

            foreach ($asistencias as $a) {
                $estado = strtolower($a->getAsistencia());
                if ($estado === 'presente') {
                    $presentes++;
                } elseif ($estado === 'ausente') {
                    $ausentes++;
                } elseif ($estado === 'media falta') {
                    $mediaFaltas++;
                } elseif ($estado === 'justificada') {
                    $justificadas++;
                }
            }

            $total = $presentes + $ausentes + $mediaFaltas + $justificadas;
            $porcentajePresente = $total > 0 ? (($presentes + $mediaFaltas * 0.5) / $total) * 100 : 0;
            $porcentajeAusente = $total > 0 ? (($ausentes + $mediaFaltas * 0.5) / $total) * 100 : 0;
            $porcentajeJustificada = $total > 0 ? ($justificadas / $total) * 100 : 0;

            $data[] = [
                'id' => $cursada->getId(),
                'nombre' => $alumno->getNombre(),
                'apellido' => $alumno->getApellido(),
                'dni' => $alumno->getDniPasaporte(),
                'asistencia' => $asistenciaHoy ? $asistenciaHoy->getAsistencia() : 'No marcado',
                'observacion' => $asistenciaHoy ? $asistenciaHoy->getObservacion() : '',
            'fecha_asistencia' => ($asistenciaHoy && $asistenciaHoy->getCalendarioClase()) 
                ? $asistenciaHoy->getCalendarioClase()->getFecha()->format('Y-m-d') 
                : null,

                'estadisticas' => [
                    'presentes' => $presentes,
                    'ausentes' => $ausentes,
                    'media_faltas' => $mediaFaltas,
                    'justificadas' => $justificadas,
                    'total' => $total,
                    'porcentaje_presente' => round($porcentajePresente, 2),
                    'porcentaje_ausente' => round($porcentajeAusente, 2),
                    'porcentaje_justificada' => round($porcentajeJustificada, 2),
                ],
            ];
        }

        return new JsonResponse([
            'fecha_backend' => $hoyFormato,
            'data' => $data,
        ]);
    }


        #[Route('/estadisticas/{curso_id}', name: 'estadisticas', methods: ['GET'])]
    public function actualizarestadisticas(
        int $curso_id,
        CursoRepository $cursoRepository,
        CursadaRepository $cursadaRepository
    ): JsonResponse {
        // Establecer la zona horaria de Buenos Aires para evitar desfases
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $curso = $cursoRepository->find($curso_id);

        if (!$curso) {
            return new JsonResponse(['error' => 'Curso no encontrado'], 404);
        }

        $cursadas = $cursadaRepository->findBy(['curso' => $curso]);

        $data = [];
        $hoy = new \DateTime();
        $hoyFormato = $hoy->format('Y-m-d');

        foreach ($cursadas as $cursada) {
            $alumno = $cursada->getAlumno();
            $asistencias = $cursada->getAsistencias();

            $asistenciaHoy = null;

            foreach ($asistencias as $a) {
                if ($a->getFecha()->format('Y-m-d') === $hoyFormato) {
                    $asistenciaHoy = $a;
                    break;
                }
            }

            // Estadísticas
            $presentes = 0;
            $ausentes = 0;
            $mediaFaltas = 0;
            $justificadas = 0;

            foreach ($asistencias as $a) {
                $estado = strtolower($a->getAsistencia());
                if ($estado === 'presente') {
                    $presentes++;
                } elseif ($estado === 'ausente') {
                    $ausentes++;
                } elseif ($estado === 'media falta') {
                    $mediaFaltas++;
                } elseif ($estado === 'justificada') {
                    $justificadas++;
                }
            }

            $total = $presentes + $ausentes + $mediaFaltas + $justificadas;
            $porcentajePresente = $total > 0 ? (($presentes + $mediaFaltas * 0.5) / $total) * 100 : 0;
            $porcentajeAusente = $total > 0 ? (($ausentes + $mediaFaltas * 0.5) / $total) * 100 : 0;
            $porcentajeJustificada = $total > 0 ? ($justificadas / $total) * 100 : 0;

            $data[] = [
                'id' => $cursada->getId(),
                'nombre' => $alumno->getNombre(),
                'apellido' => $alumno->getApellido(),
                'dni' => $alumno->getDniPasaporte(),
                'asistencia' => $asistenciaHoy ? $asistenciaHoy->getAsistencia() : 'No marcado',
                'observacion' => $asistenciaHoy ? $asistenciaHoy->getObservacion() : '',
                'fecha_asistencia' => $asistenciaHoy ? $asistenciaHoy->getFecha()->format('Y-m-d') : null,
                'estadisticas' => [
                    'presentes' => $presentes,
                    'ausentes' => $ausentes,
                    'media_faltas' => $mediaFaltas,
                    'justificadas' => $justificadas,
                    'total' => $total,
                    'porcentaje_presente' => round($porcentajePresente, 2),
                    'porcentaje_ausente' => round($porcentajeAusente, 2),
                    'porcentaje_justificada' => round($porcentajeJustificada, 2),
                ],
            ];
        }

        return new JsonResponse($data);
    }
                
    #[Route('/newcalendario', name: 'newcalendario', methods: ['POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (!$data) {
        return $this->json(['error' => 'No se recibió un JSON válido'], 400);
    }

    if (!isset($data['modalidad'], $data['curso'], $data['fecha'])) {
        return $this->json(['error' => 'Faltan datos requeridos'], 400);
    }

    // Zona horaria Argentina
    $zona = new \DateTimeZone('America/Argentina/Buenos_Aires');

    try {
        // Crear objeto DateTime sin zona horaria y luego asignar zona horaria correcta
        $fecha = new \DateTime($data['fecha']);
        $fecha->setTimezone($zona);
        $fecha->setTime(0, 0, 0);
    } catch (\Exception $e) {
        return $this->json(['error' => 'Fecha no válida'], 400);
    }

    // Verificar si ya existe un calendario para ese curso y fecha
    $calendarioExistente = $entityManager
        ->getRepository(\App\Entity\CalendarioClase::class)
        ->findByFechaAndCurso($fecha, (int)$data['curso']);

    if ($calendarioExistente) {
        $calendarioClase = $calendarioExistente;
    } else {
        $calendarioClase = new CalendarioClase();
    }

    // Buscar modalidad
    $modalidad = $entityManager->getRepository(\App\Entity\Modalidad::class)->find($data['modalidad']);
    if (!$modalidad) {
        return $this->json(['error' => 'Modalidad no válida'], 400);
    }

    // Buscar curso
    $curso = $entityManager->getRepository(\App\Entity\Curso::class)->find($data['curso']);
    if (!$curso) {
        return $this->json(['error' => 'Curso no válido'], 400);
    }

    // Asignar datos al objeto
    $calendarioClase->setModalidad($modalidad);
    $calendarioClase->setCurso($curso);
    $calendarioClase->setFecha($fecha);
    $calendarioClase->setObservacion($data['observacion'] ?? '');

    $entityManager->persist($calendarioClase);
    $entityManager->flush();

    return $this->json([
        'success' => true,
        'id' => $calendarioClase->getId(),
        'message' => $calendarioExistente ? 'Calendario actualizado' : 'Calendario creado',
    ]);
}



#[Route('/api/calendario-clase-del-dia/{cursoId}', name: 'api_calendario_clase_del_dia', methods: ['GET'])]
public function getCalendarioClaseDelDia(int $cursoId, CalendarioClaseRepository $repo): JsonResponse
{
    $zona = new \DateTimeZone('America/Argentina/Buenos_Aires');
    $fechaHoy = new \DateTime('today', $zona);

    error_log('Fecha hoy en getCalendarioClaseDelDia: ' . $fechaHoy->format('Y-m-d H:i:s'));

    $calendario = $repo->findByFechaAndCurso($fechaHoy, $cursoId);

    if (!$calendario) {
        return $this->json(['error' => 'No se encontró calendario para hoy'], 404);
    }

    return $this->json([
        'id' => $calendario->getId(),
        'fecha' => $calendario->getFecha()->format('Y-m-d')
    ]);
}





}


    

