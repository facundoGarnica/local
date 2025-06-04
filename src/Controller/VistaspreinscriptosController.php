<?php

namespace App\Controller;

use App\Entity\Nota;
use App\Form\NotaType;
use App\Repository\ModalidadRepository;
use App\Repository\NotaRepository;
use App\Entity\Persona;
use App\Form\PersonaType;
use App\Repository\PersonaRepository;
use App\Entity\Alumno;
use App\Form\AlumnoType;
use App\Repository\AlumnoRepository;
use App\Entity\Carreras;
use App\Form\CarrerasType;
use App\Repository\CarrerasRepository;
use App\Entity\Cursada;
use App\Form\CursadaType;
use App\Repository\CursadaRepository;
use App\Entity\Curso;
use App\Form\CursoType;
use App\Repository\CursoRepository;
use App\Entity\Tecnicatura;
use App\Form\TecnicaturaType;
use App\Repository\TecnicaturaRepository;
use App\Repository\InstitutoRepository;
use App\Service\PreinscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;   //para captar campos unicos que queremos duplicar y la base no nos deja por consecuencia
use Symfony\Component\HttpFoundation\JsonResponse;

class VistaspreinscriptosController extends AbstractController
{

    private $preinscriptionService;

    public function __construct(PreinscriptionService $preinscriptionService)
    {
        $this->preinscriptionService = $preinscriptionService;
    }

    #[Route('/verificarcomision/resolucion/{resolucion}/N2/{N2}/anio/{anio}', name: 'verificar_comision', methods: ['GET'])]
    public function verificarComision(
        CursoRepository $cursoRepository, 
        string $resolucion, 
        string $N2, 
        int $anio
    ): JsonResponse {
        // Reconstrucción de la resolución
        $resolucionCompleta = $resolucion . '/' . $N2;
    
        // Buscar la tecnicatura por la resolución completa
        $tecnicatura = $this->obtenerTecnicaPorResolucion($resolucionCompleta);

        if (!$tecnicatura) {
            return $this->json(['error' => 'Tecnicatura no encontrada para la resolución dada.']);
        }
    
        // Buscar comisiones de 1er año para la tecnicatura y año ingresado
        $comisionesPrimerAnio = $cursoRepository->createQueryBuilder('curso')
            ->join('curso.comision', 'comision')
            ->where('comision.tecnicatura = :tecnicatura')
            ->andWhere('comision.anio = 1') // Filtrar solo comisiones de 1° año
            ->andWhere('curso.ciclo_lectivo = :anio')
            ->setParameter('tecnicatura', $tecnicatura)
            ->setParameter('anio', $anio)
            ->getQuery()
            ->getResult();
    
        if (!$comisionesPrimerAnio) {
            return $this->json(['error' => 'No se encontraron comisiones de 1° año para la tecnicatura en el año indicado.']);
        }
    
        return $this->json(['success' => 'Se encontraron comisiones de 1° año disponibles.']);
    }
    
    #[Route('/cursadaasignatura/{dni}/resolucion/{resolucion}/N2/{N2}/id/{id}/anio/{anio}', name: 'crear_asignaturasDe1', methods: ['GET', 'POST'])]
    public function CrearCursadasDeAsignaturas(AlumnoRepository $alumnoRepository,ModalidadRepository $modalidadRepository, CursoRepository $cursoRepository, CursadaRepository $cursadaRepository, string $dni, string $resolucion, string $N2 ,int $id, int $anio,Request $request): Response
    {
        // Recuperar la sesión
        $session = $request->getSession();    
        // Limpiar cualquier valor previo en la sesión relacionado con el DNI
        $session->remove('dni');  
        //reconstruccion de la resolucion separada por / para evitar conflictos en la ruta
        $resolucionCompleta = $resolucion . '/' . $N2;

        // Recuperar la modalidad predeterminada (ID 1)
        $modalidadPresencial = $modalidadRepository->find(1);

        // Consultar el estudiante por `dni_pasaporte` usando el nuevo método del repositorio
        $alumno = $alumnoRepository->findByDniPasaporte($dni);
        if (!$alumno) {
            return $this->json(['error' => 'Estudiante no encontrado']);
        }
    
        // Buscar la tecnicatura por la resolución completa
        $tecnicatura = $this->obtenerTecnicaPorResolucion($resolucionCompleta);

        if (!$tecnicatura) {
            return $this->json(['error' => 'Tecnicatura no encontrada para la resolución dada.']);
        }

        // Obtener el año actual  (cuando se inscriba debemos tomar una desicion, si pasarlo por parametros esta fecha o bien siempre sumarle un año más)
        //$anioActual = date('Y');
        $anioActual = $anio;

        // Buscar comisiones del primer año de la tecnicatura
        $comisionesPrimerAnio = $cursoRepository->createQueryBuilder('curso')
            ->join('curso.comision', 'comision')
            ->where('comision.tecnicatura = :tecnicatura')
            ->andWhere('comision.anio = 1') // Filtrar solo comisiones de 1° año
            ->andWhere('curso.ciclo_lectivo = :anioActual')
            ->setParameter('tecnicatura', $tecnicatura)
            ->setParameter('anioActual', $anioActual)
            ->getQuery()
            ->getResult();

        if (!$comisionesPrimerAnio) {

            return $this->json(['error' => 'No se encontraron comisiones para el primer año en la tecnicatura seleccionada.']);
        }   
        foreach ($comisionesPrimerAnio as $curso) {

            // Verificar si ya existe una cursada para el alumno en el curso
            $existingCursada = $cursadaRepository->findOneBy(['alumno' => $alumno, 'curso' => $curso]);
            
            if ($existingCursada) {
                //si el alumno ya esta en las cursadas inscripto
                // Después de guardar en la DB, actualizar el CSV
                $filename = 'preinscripciones_instituto_copy.csv'; 
                $filePath = '../public/preinscriptos/' . $filename;
                $data = $this->preinscriptionService->readCsvCopy($filePath);
                // Encontrar el registro en el CSV que coincide con el DNI
                foreach ($data as &$entry) {
                    if ($entry['id'] == $id) { 
                        $entry['new_field'] = '4'; // Actualizar el campo con el valor '4'
                        break;
                    }
                }
                // Guardar los cambios en el archivo CSV
                $this->preinscriptionService->updateCsv($filePath, $data);   
                return $this->json(['error' => 'El alumno ya está inscrito en asignaturas de 1ro.']);
            }

            $cursada = new Cursada();
            $cursada->setAlumno($alumno);
            $cursada->setCurso($curso);
            $cursada->setCondicion('regular');
            $cursada->setModalidad($modalidadPresencial);    
                // Crear una nueva instancia de Nota
                $nota = new Nota();
                $nota->setParcial(''); 
                $nota->setRecuperatorio1('');
                $nota->setParcial2('');
                $nota->setRecuperatorio2('');    
                // Asignar la nota creada a la cursada
                $cursada->setNotaId($nota);  
                // Guardar la cursada (y la nota asociada debido a la relación en cascada)
                $cursadaRepository->save($cursada, true);
        }  
        

        // Después de guardar en la DB, actualizar el CSV
        $filename = 'preinscripciones_instituto_copy.csv'; 
        $filePath = '../public/preinscriptos/' . $filename;
        $data = $this->preinscriptionService->readCsvCopy($filePath);
        // Encontrar el registro en el CSV que coincide con el DNI
        foreach ($data as &$entry) {
            if ($entry['id'] == $id) { 
                $entry['new_field'] = '4'; // Actualizar el campo con el valor '4'
                break;
            }
        }
        // Guardar los cambios en el archivo CSV
        $this->preinscriptionService->updateCsv($filePath, $data); 
        return $this->json(['error' => 'El estudiante se inscribio con exito a las asignaturas de 1 año!!!.']);

        return $this->redirectToRoute('app_vistaspreinscriptos', [], Response::HTTP_SEE_OTHER);
    }


    private function obtenerTecnicaPorResolucion($resolucionCompleta)
    {
        // Lógica para encontrar la tecnicatura según la resolución
        $tecnicatura = $this->getDoctrine()->getRepository(Tecnicatura::class)->findOneBy(['numero_resolucion' => $resolucionCompleta]);
        if (!$tecnicatura) {
            return $this->json(['error' => 'Tecnicatura no encontrada']);
        }
        return $tecnicatura;
    }


    //INDEX
    #[Route('/vistaspreinscriptos', name: 'app_vistaspreinscriptos')]
    public function index(InstitutoRepository $institutoRepository, PersonaRepository $personaRepository, AlumnoRepository $alumnoRepository, CarrerasRepository $carrerasRepository, Request $request): Response
    {
        // Obtener el CUE del instituto actual
        $instituto = $institutoRepository->findOneBy(['id' => 1]);
        $numeroCue = $instituto ? $instituto->getNumeroCue() : null;
    
        // Generar el CSV automáticamente si se tiene el CUE
        if ($numeroCue) {
            $data = $this->preinscriptionService->fetchPreinscriptionDataByInstitute($numeroCue);
            $filename = $this->preinscriptionService->generateCsv($data, 'preinscripciones_instituto');
        }
    
        $filename = 'preinscripciones_instituto.csv';
        $filePath = '../public/preinscriptos/' . $filename;
        $data = null;
    
        if (file_exists($filePath) && $this->isValidCsv($filePath)) {
            $data = $this->preinscriptionService->readCsv($filePath);
        }
    
        // Automáticamente hacer la copia y modificar el CSV
        $filename2 = 'preinscripciones_instituto_copy.csv';
        $filePath2 = '../public/preinscriptos/' . $filename2;
        $data2 = null;
    
        try {
            if (file_exists($filePath)) {
                // Intentar hacer la copia y agregar el campo
                $filePath2 = $this->preinscriptionService->copyAndAddField($filePath);
                // Si la operación es exitosa, agrega un mensaje de éxito, quitado el mensaje por que una vez logrado molesta en la UX
                //$this->addFlash('success', 'La copia y modificación del archivo CSV se realizó correctamente.');
            }
        } catch (\RuntimeException $e) {
            // Manejo de la excepción
            $this->addFlash('error', $e->getMessage());
        }
    
        if (file_exists($filePath2) && $this->isValidCsv($filePath2)) {
            $data2 = $this->preinscriptionService->readCsvCopy($filePath2);
        }
 
        // Serializar solo los DNIs en un array
        $personas = $personaRepository->findAll();
        $dniList = array_map(function($persona) {
            return $persona->getDniPasaporte();
        }, $personas);

        // Serializar solo los DNIs en un array
        $alumnos = $alumnoRepository->findAll();
        // Crear un array de DNIs de personas asociadas a los alumnos
        $dniListAlu = array_map(function($alumno) {
            // Obtener la persona asociada al alumno y su DNI
            $persona = $alumno->getPersona();
            return $persona ? $persona->getDniPasaporte() : null;
        }, $alumnos);


        // Serializar los DNIs junto con las carreras en un array
        $carreras = $carrerasRepository->findAll();
        $dniListCar = array_map(function($carrera) {
            // Obtener el alumno asociado a la carrera
            $alumno = $carrera->getEstudianteId();
            // Obtener la persona asociada al alumno y su DNI
            $persona = $alumno ? $alumno->getPersona() : null;
            $tecnicatura = $carrera->getTecnicaturaId();

            return $persona ? [
                'dni' => $persona->getDniPasaporte(),
                'tecnicatura' => $tecnicatura ? $tecnicatura->getNumeroResolucion() : null
            ] : null;
        }, $carreras);
        // Filtrar para eliminar valores nulos
        $dniListCar = array_filter($dniListCar);


        $session = $request->getSession();
        $DNI = $session->get('dni', null);
        if ($DNI === null) {
            $DNI = null; 
        } else {
            $session->remove('dni');
        }

        return $this->render('vistaspreinscriptos/index.html.twig', [
            'dni' => $DNI,
            'copied_data' => $data2,
            'data' => $data,
            'filename' => $filename,
            'path' => $filePath,
            'institutos' => $institutoRepository->findAll(),
            'personas' => json_encode($dniList),
            'alumnos' => json_encode($dniListAlu),
            'carreras' => json_encode($dniListCar),
        ]);
    }



    //2025 ahora del index todo debe pasar por edit que sera la evaluacion total de los campo sen la base de datos
    //ademas de incorporar toda la logica de inscripcion de una persona al instituto (persona->alumno->carrera->asignaturas de 1ro) 
    #[Route('/vistaspreinscriptos/edit/{id}', name: 'app_vistaspreinscriptos_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, InstitutoRepository $institutoRepository, PersonaRepository $personaRepository, AlumnoRepository $alumnoRepository, CarrerasRepository $carrerasRepository, Request $request): Response
    {

        

        // Serializar solo los DNIs en un array
        $personas = $personaRepository->findAll();
        $dniList = array_map(function($persona) {
            return $persona->getDniPasaporte();
        }, $personas);

        // Serializar solo los DNIs en un array
        $alumnos = $alumnoRepository->findAll();
        // Crear un array de DNIs de personas asociadas a los alumnos
        $dniListAlu = array_map(function($alumno) {
            // Obtener la persona asociada al alumno y su DNI
            $persona = $alumno->getPersona();
            return $persona ? $persona->getDniPasaporte() : null;
        }, $alumnos);


        // Serializar los DNIs junto con las carreras en un array
        $carreras = $carrerasRepository->findAll();
        $dniListCar = array_map(function($carrera) {
            // Obtener el alumno asociado a la carrera
            $alumno = $carrera->getEstudianteId();
            // Obtener la persona asociada al alumno y su DNI
            $persona = $alumno ? $alumno->getPersona() : null;
            $tecnicatura = $carrera->getTecnicaturaId();

            return $persona ? [
                'dni' => $persona->getDniPasaporte(),
                'tecnicatura' => $tecnicatura ? $tecnicatura->getNumeroResolucion() : null
            ] : null;
        }, $carreras);
        // Filtrar para eliminar valores nulos
        $dniListCar = array_filter($dniListCar);


        $session = $request->getSession();
        $DNI = $session->get('dni', null);
        if ($DNI === null) {
            $DNI = null; 
        } else {
            $session->remove('dni');
        }





        $filename = 'preinscripciones_instituto_copy.csv'; // Asegúrate de que sea el correcto
        $filePath = '../public/preinscriptos/' . $filename;
        $data = $this->preinscriptionService->readCsvCopy($filePath);

        // Encuentra el registro específico por ID
        $recordToUpdate = null;
        foreach ($data as &$entry) {
            if ($entry['id'] == $id) {
                $recordToUpdate = &$entry;
                break;
            }
        }

        if (!$recordToUpdate) {
            throw $this->createNotFoundException('Registro no encontrado.');
        }

        if ($request->isMethod('POST')) {
            // Actualiza el registro con los datos del formulario  -Revisar si se llegan a necesitar mas edicion modificar aqui tambien-
            $recordToUpdate['persona_nombre'] = $request->request->get('persona_nombre', $recordToUpdate['persona_nombre']);
            $recordToUpdate['persona_apellido'] = $request->request->get('persona_apellido', $recordToUpdate['persona_apellido']);
            $recordToUpdate['fecha_nacimiento'] = $request->request->get('fecha_nacimiento', $recordToUpdate['fecha_nacimiento']);
            $recordToUpdate['dni_pasaporte'] = $request->request->get('dni_pasaporte', $recordToUpdate['dni_pasaporte']);
            $recordToUpdate['tecnicatura_nombre'] = $request->request->get('tecnicatura_nombre', $recordToUpdate['tecnicatura_nombre']);
            $recordToUpdate['tecnicatura_resolucion'] = $request->request->get('tecnicatura_resolucion', $recordToUpdate['tecnicatura_resolucion']);
            $recordToUpdate['turno_descripcion'] = $request->request->get('turno_descripcion', $recordToUpdate['turno_descripcion']);
            $recordToUpdate['instituto_numero'] = $request->request->get('instituto_numero', $recordToUpdate['instituto_numero']);
            $recordToUpdate['instituto_cue'] = $request->request->get('instituto_cue', $recordToUpdate['instituto_cue']);
            $recordToUpdate['new_field'] = $request->request->get('new_field', $recordToUpdate['new_field']);

            // Actualiza el archivo CSV con el registro modificado
            $this->preinscriptionService->updateCsv($filePath, $data);
            
            return $this->redirectToRoute('app_vistaspreinscriptos'); // Redirige a la lista o a la vista deseada
        }

        return $this->render('vistaspreinscriptos/edit.html.twig', [
            'dni' => $DNI,
            'institutos' => $institutoRepository->findAll(),
            'personas' => json_encode($dniList),
            'alumnos' => json_encode($dniListAlu),
            'carreras' => json_encode($dniListCar),

            'record' => $recordToUpdate,
        ]);
    }


    #[Route('/vistaspreinscriptos/clear_files', name: 'app_vistaspreinscriptos_clear_files', methods: ['POST'])]
    public function clearCsvFiles(): Response
    {
        $originalFilePath = '../public/preinscriptos/preinscripciones_instituto.csv';
        $copyFilePath = '../public/preinscriptos/preinscripciones_instituto_copy.csv';
    
        // Limpiar el archivo original, conservando la primera fila
        if (file_exists($originalFilePath)) {
            $this->clearCsvFile($originalFilePath);
        }
    
        // Limpiar el archivo copia, conservando la primera fila
        if (file_exists($copyFilePath)) {
            $this->clearCsvFile($copyFilePath);
        }
    
        // Redirigir de nuevo a la página con un mensaje de éxito
        return $this->redirectToRoute('app_vistaspreinscriptos', [
            'success_message' => 'Los archivos CSV han sido limpiados exitosamente.'
        ]);
    }
    

    /**
     * Limpia el archivo CSV conservando la primera fila (encabezado).
     *
     * @param string $filePath
     */
    private function clearCsvFile(string $filePath): void
    {
        $header = null;
        $tempFilePath = $filePath . '.tmp';
    
        // Leer el archivo original y escribir el encabezado en un archivo temporal
        if (($handle = fopen($filePath, 'r')) !== false) {
            if (($header = fgetcsv($handle)) !== false) {
                $tempHandle = fopen($tempFilePath, 'w');
                fputcsv($tempHandle, $header);
                fclose($tempHandle);
            }
            fclose($handle);
        }
    
        // Reemplazar el archivo original con el archivo temporal
        if (file_exists($tempFilePath)) {
            rename($tempFilePath, $filePath);
        }
    }


    //Genera la copia filtrada por el numero de cue de region en un archivo csv para guardarlo en local
    #[Route('/vistaspreinscriptos/generate_filtered', name: 'app_vistaspreinscriptos_generate_filtered', methods: ['POST'])]
    public function generateFilteredCsv(Request $request): Response
    {
        $instituteId = $request->request->get('institute_id');
        $data = $this->preinscriptionService->fetchPreinscriptionDataByInstitute($instituteId);
        $filename = $this->preinscriptionService->generateCsv($data, 'preinscripciones_instituto');
    
        // Redirigir a la página de índice con un mensaje de éxito
        return $this->render('vistaspreinscriptos/index.html.twig', [
            'success_message' => 'El archivo CSV ha sido generado exitosamente.'
        ]);
    }


    // Ruta para copiar y modificar el CSV
    #[Route('/vistaspreinscriptos/copy_and_modify', name: 'app_vistaspreinscriptos_copy_and_modify', methods: ['POST'])]
    public function copyAndModifyCsv(Request $request): Response
    {

        $filename = 'preinscripciones_instituto.csv';
        $filePath = '../public/preinscriptos/' . $filename;

        if (file_exists($filePath) && $this->isValidCsv($filePath)) {
            $newFilePath = $this->preinscriptionService->copyAndAddField($filePath);

            return $this->render('vistaspreinscriptos/index.html.twig', [
                'new_file_path' => $newFilePath,
                'success_message' => 'El archivo ha sido copiado y modificado exitosamente.',
            ]);
        } else {
            return $this->render('vistaspreinscriptos/index.html.twig', [
                'error_message' => 'El archivo CSV no es válido o está vacío.',
            ]);
        }
    }    
 

    // Método para verificar si el archivo CSV tiene un encabezado válido
    private function isValidCsv(string $filePath): bool
    {
        // Verificar si el archivo tiene al menos una línea
        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle);
            fclose($handle);

            // Verificar si la línea del encabezado no está vacía
            return $header !== false && !empty(array_filter($header));
        }

        return false;
    }


    // Ruta para leer y mostrar el archivo copiado
    #[Route('/vistaspreinscriptos/read_copied', name: 'app_vistaspreinscriptos_read_copied', methods: ['POST'])]
    public function readCopiedCsv(Request $request): Response
    {
        $filename = $request->request->get('filename');
        $data = $this->preinscriptionService->readCsvCopy();
    
        return $this->render('vistaspreinscriptos/index.html.twig', [
            'copied_data' => $data,
            'copied_filename' => $filename,
        ]);
    }




    #[Route('/vistaspreinscriptos/generate', name: 'app_vistaspreinscriptos_generate')]
    public function generateCsvFiles(): Response
    {
        $files = $this->preinscriptionService->generateAllCsv();

        return $this->redirectToRoute('app_vistaspreinscriptos_list');
    }

    #[Route('/vistaspreinscriptos/list', name: 'app_vistaspreinscriptos_list')]
    public function listFiles(): Response
    {
        $directory = '../public/preinscriptos';
        $finder = new Finder();
        $finder->files()->in($directory);

        $files = [];
        foreach ($finder as $file) {
            $files[] = $file->getRelativePathname();
        }

        return $this->render('vistaspreinscriptos/list.html.twig', [
            'files' => $files,
        ]);
    }

    #[Route('/vistaspreinscriptos/download/{filename}', name: 'app_vistaspreinscriptos_download')]
    public function download(string $filename): Response
    {
        $filePath = '../public/preinscriptos/' . $filename;
        return $this->file($filePath, $filename, ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }


    //crear persona en el sistema de bsd local
    #[Route('/nuevapersonaPre/{dni}/id/{id}', name: 'crear_persona_pre', methods: ['GET', 'POST'])]
    public function nuevapersonaPre(Request $request, PersonaRepository $personaRepository, string $dni, int $id): Response
    {
        // Recuperar la sesión
        $session = $request->getSession();

        // Limpiar cualquier valor previo en la sesión relacionado con el DNI
        $session->remove('dni');

        // Verificar si el DNI no tiene el prefijo '1.' para evitar duplicados
        if ($dni !== null && !str_starts_with($dni, '1.')) {
            $session->set('dni', '1.' . $dni);
        } else {
            // Si ya tiene el prefijo, lo guardamos tal cual
            $session->set('dni', $dni);
        }

        // Obtener el valor del DNI desde la sesión
        $dni = $session->get('dni');

        $persona = new Persona();
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);

        // Captando posible dni duplicado en la db
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Guardar la nueva persona en la base de datos
                $personaRepository->save($persona, true);

                // Después de guardar en la DB, actualizar el CSV
                $filename = 'preinscripciones_instituto_copy.csv'; 
                $filePath = '../public/preinscriptos/' . $filename;
                $data = $this->preinscriptionService->readCsvCopy($filePath);

                // Encontrar el registro en el CSV que coincide con el DNI
                foreach ($data as &$entry) {
                    if ($entry['id'] == $id) { 
                        $entry['new_field'] = '1'; // Actualizar el campo con el valor '1'
                        break;
                    }
                }

                // Guardar los cambios en el archivo CSV
                $this->preinscriptionService->updateCsv($filePath, $data);

                // Redirigir después de la actualización
                return $this->redirectToRoute('app_vistaspreinscriptos', [], Response::HTTP_SEE_OTHER);
            } catch (UniqueConstraintViolationException $e) {
                // Manejar el caso de DNI duplicado
                $session->remove('dni'); // Limpiar el DNI de la sesión
                $this->addFlash('errorDNI', 'Entrada repetida de DNI');
            } catch (\Exception $e) {
                $session->remove('dni'); // Limpiar el DNI de la sesión
                $this->addFlash('errorDNI', 'Ocurrió un error al guardar la persona');
            }
        }

        return $this->renderForm('vistaspreinscriptos/create_persona_form.html.twig', [
            'dni' => $dni,
            'id' => $id,
            'persona' => $persona,
            'form' => $form,
        ]);
    }


    //SI PERSONA EXISTE ENTRA A ESTA FUNCION PARA PROSEGUIR LA SECUENACIA!!!!!!
    #[Route('/existePersona/{dni}', name: 'persona_siguiente', methods: ['GET', 'POST'])]
    public function siguientePersonaPer(Request $request, string $dni, InstitutoRepository $institutoRepository, PersonaRepository $personaRepository, AlumnoRepository $alumnoRepository, CarrerasRepository $carrerasRepository): Response
    {
        // Recuperar la sesión
        $session = $request->getSession();    
        // Limpiar cualquier valor previo en la sesión relacionado con el DNI
        //$session->remove('dni');  
        // Verificar si el DNI no tiene el prefijo '1.' para evitar duplicados
        if ($dni !== null && !str_starts_with($dni, '1.')) {
            $session->set('dni', '1.' . $dni);
        } else {
            // Si ya tiene el prefijo, lo guardamos tal cual
            $session->set('dni', $dni);
        }
        // Obtener el valor del DNI desde la sesión
        $dni = $session->get('dni');            
        //return $this->redirectToRoute('app_vistaspreinscriptos', [], Response::HTTP_SEE_OTHER);
      
        // Obtener el CUE del instituto actual
        $instituto = $institutoRepository->findOneBy(['id' => 1]);
        $numeroCue = $instituto ? $instituto->getNumeroCue() : null;
        
        // Generar el CSV automáticamente si se tiene el CUE
        if ($numeroCue) {
            $data = $this->preinscriptionService->fetchPreinscriptionDataByInstitute($numeroCue);
            $filename = $this->preinscriptionService->generateCsv($data, 'preinscripciones_instituto');
        }
        
        $filename = 'preinscripciones_instituto.csv';
        $filePath = '../public/preinscriptos/' . $filename;
        $data = null;
        
        if (file_exists($filePath) && $this->isValidCsv($filePath)) {
            $data = $this->preinscriptionService->readCsv($filePath);
        }
        
        // Automáticamente hacer la copia y modificar el CSV
        $filename2 = 'preinscripciones_instituto_copy.csv';
        $filePath2 = '../public/preinscriptos/' . $filename2;
        $data2 = null;
        
        if (file_exists($filePath)) {
            $filePath2 = $this->preinscriptionService->copyAndAddField($filePath);
        }
        
        if (file_exists($filePath2) && $this->isValidCsv($filePath2)) {
            $data2 = $this->preinscriptionService->readCsvCopy($filePath2);
        }

        // Serializar solo los DNIs en un array
        $personas = $personaRepository->findAll();
        $dniList = array_map(function($persona) {
            return $persona->getDniPasaporte();
        }, $personas);

        // Serializar solo los DNIs en un array
        $alumnos = $alumnoRepository->findAll();
        // Crear un array de DNIs de personas asociadas a los alumnos
        $dniListAlu = array_map(function($alumno) {
            // Obtener la persona asociada al alumno y su DNI
            $persona = $alumno->getPersona();
            return $persona ? $persona->getDniPasaporte() : null;
        }, $alumnos);



        $carreras = $carrerasRepository->findAll();
        $dniListCar = array_map(function($carrera) {
            // Obtener el alumno asociado a la carrera
            $alumno = $carrera->getEstudianteId();
            // Obtener la persona asociada al alumno y su DNI
            $persona = $alumno ? $alumno->getPersona() : null;
            $tecnicatura = $carrera->getTecnicaturaId();

            return $persona ? [
                'dni' => $persona->getDniPasaporte(),
                'tecnicatura' => $tecnicatura ? $tecnicatura->getNumeroResolucion() : null
            ] : null;
        }, $carreras);
        // Filtrar para eliminar valores nulos
        $dniListCar = array_filter($dniListCar);


        return $this->render('vistaspreinscriptos/index.html.twig', [
            'dni' => $dni,
            'copied_data' => $data2,
            'data' => $data,
            'filename' => $filename,
            'path' => $filePath,
            'institutos' => $institutoRepository->findAll(),
            'personas' => json_encode($dniList),
            'alumnos' => json_encode($dniListAlu),

            'carreras' => json_encode($dniListCar),
        ]);
    }


    //CREAR ALUMNO!!!!!
    #[Route('/nuevoalumnoPre/{dni}/id/{id}', name: 'crear_alumno_pre', methods: ['GET', 'POST'])]
    public function nuevoalumnoPre(Request $request, AlumnoRepository $alumnoRepository, string $dni, int $id): Response
    {
        $dniOriginal = $dni; //para la busqueda en el csv

        // Recuperar la sesión
        $session = $request->getSession();

        // Limpiar cualquier valor previo en la sesión relacionado con el DNI
        $session->remove('dni');

        // Verificar si el DNI no tiene el prefijo '1.' para evitar duplicados
        if ($dni !== null && !str_starts_with($dni, '2.')) {
            $session->set('dni', '2.' . $dni);
        } else {
            // Si ya tiene el prefijo, lo guardamos tal cual
            $session->set('dni', $dni);
        }
        // Obtener el valor del DNI desde la sesión
        $dni = $session->get('dni'); 
        $alumno = new Alumno();
        $form = $this->createForm(AlumnoType::class, $alumno);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $alumnoRepository->save($alumno, true);

            // Después de guardar en la DB, actualizar el CSV
            $filename = 'preinscripciones_instituto_copy.csv'; 
            $filePath = '../public/preinscriptos/' . $filename;
            $data = $this->preinscriptionService->readCsvCopy($filePath);
            // Encontrar el registro en el CSV que coincide con el DNI
            foreach ($data as &$entry) {
                if ($entry['id'] == $id) { // campo 'dni' en el CSV
                    $entry['new_field'] = '2'; // Actualizar el campo con el valor '1'
                    break;
                }
            }
            // Guardar los cambios en el archivo CSV
            $this->preinscriptionService->updateCsv($filePath, $data);    

            return $this->redirectToRoute('app_vistaspreinscriptos', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('vistaspreinscriptos/create_alumno_form.html.twig', [
            'dni' => $dni,
            'id' => $id,
            'alumno' => $alumno,
            'form' => $form,
        ]);
    }



    //SI ALUMNO EXISTE ENTRA A ESTA FUNCION PARA PROSEGUIR LA SECUENACIA!!!!!!
    #[Route('/existeAlumno/{dni}', name: 'alumno_siguiente', methods: ['GET', 'POST'])]
    public function siguienteAlumno(Request $request, string $dni, InstitutoRepository $institutoRepository, PersonaRepository $personaRepository, AlumnoRepository $alumnoRepository, CarrerasRepository $carrerasRepository,): Response
    {
        // Recuperar la sesión
        $session = $request->getSession();    
        // Limpiar cualquier valor previo en la sesión relacionado con el DNI
        //$session->remove('dni');  
        // Verificar si el DNI no tiene el prefijo '1.' para evitar duplicados
        if ($dni !== null && !str_starts_with($dni, '2.')) {
            $session->set('dni', '2.' . $dni);
        } else {
            // Si ya tiene el prefijo, lo guardamos tal cual
            $session->set('dni', $dni);
        }
        // Obtener el valor del DNI desde la sesión
        $dni = $session->get('dni');            
        //return $this->redirectToRoute('app_vistaspreinscriptos', [], Response::HTTP_SEE_OTHER);
      
        // Obtener el CUE del instituto actual
        $instituto = $institutoRepository->findOneBy(['id' => 1]);
        $numeroCue = $instituto ? $instituto->getNumeroCue() : null;
        
        // Generar el CSV automáticamente si se tiene el CUE
        if ($numeroCue) {
            $data = $this->preinscriptionService->fetchPreinscriptionDataByInstitute($numeroCue);
            $filename = $this->preinscriptionService->generateCsv($data, 'preinscripciones_instituto');
        }
        
        $filename = 'preinscripciones_instituto.csv';
        $filePath = '../public/preinscriptos/' . $filename;
        $data = null;
        
        if (file_exists($filePath) && $this->isValidCsv($filePath)) {
            $data = $this->preinscriptionService->readCsv($filePath);
        }
        
        // Automáticamente hacer la copia y modificar el CSV
        $filename2 = 'preinscripciones_instituto_copy.csv';
        $filePath2 = '../public/preinscriptos/' . $filename2;
        $data2 = null;
        
        if (file_exists($filePath)) {
            $filePath2 = $this->preinscriptionService->copyAndAddField($filePath);
        }
        
        if (file_exists($filePath2) && $this->isValidCsv($filePath2)) {
            $data2 = $this->preinscriptionService->readCsvCopy($filePath2);
        }

        // Serializar solo los DNIs en un array
        $personas = $personaRepository->findAll();
        $dniList = array_map(function($persona) {
            return $persona->getDniPasaporte();
        }, $personas);

        // Serializar solo los DNIs en un array
        $alumnos = $alumnoRepository->findAll();
        // Crear un array de DNIs de personas asociadas a los alumnos
        $dniListAlu = array_map(function($alumno) {
            // Obtener la persona asociada al alumno y su DNI
            $persona = $alumno->getPersona();
            return $persona ? $persona->getDniPasaporte() : null;
        }, $alumnos);



        $carreras = $carrerasRepository->findAll();
        $dniListCar = array_map(function($carrera) {
            // Obtener el alumno asociado a la carrera
            $alumno = $carrera->getEstudianteId();
            // Obtener la persona asociada al alumno y su DNI
            $persona = $alumno ? $alumno->getPersona() : null;
            $tecnicatura = $carrera->getTecnicaturaId();

            return $persona ? [
                'dni' => $persona->getDniPasaporte(),
                'tecnicatura' => $tecnicatura ? $tecnicatura->getNumeroResolucion() : null
            ] : null;
        }, $carreras);
        // Filtrar para eliminar valores nulos
        $dniListCar = array_filter($dniListCar);


        return $this->render('vistaspreinscriptos/index.html.twig', [
            'dni' => $dni,
            'copied_data' => $data2,
            'data' => $data,
            'filename' => $filename,
            'path' => $filePath,
            'institutos' => $institutoRepository->findAll(),
            'personas' => json_encode($dniList),
            'alumnos' => json_encode($dniListAlu),

            'carreras' => json_encode($dniListCar),
        ]);
    }


    #[Route('/nueva/carreras/{id}/dni/{dni}', name: 'crear_carrera_Pre', methods: ['GET', 'POST'])]
    public function nuevacarreraPreinscripcion(Request $request, CarrerasRepository $carrerasRepository, int $id, string $dni): Response
    {     
        // Recuperar la sesión  nueva implementacion para mantener la session con la nueva extencion de dni en dni+3
        $session = $request->getSession();     
        // Verificar si el DNI no tiene el prefijo '3.' para evitar duplicados
        if ($dni !== null && !str_starts_with($dni, '3.')) {
            $session->set('dni', '3.' . $dni);
        } else {
            // Si ya tiene el prefijo, lo guardamos tal cual
            $session->set('dni', $dni);
        }
        // Obtener el valor del DNI desde la sesión
        $dni = $session->get('dni');  
            
        $carreras = new Carreras();
        $form = $this->createForm(CarrerasType::class, $carreras);
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {
            // Obtener el alumno y la tecnicatura desde el formulario
            $alumno = $carreras->getEstudianteId(); // Devuelve la entidad Alumno
            $tecnicatura = $carreras->getTecnicaturaId(); // Devuelve la entidad Tecnicatura
        
            // Verificar si ya existe una carrera con el mismo alumno y tecnicatura
            $carreraExistente = $carrerasRepository->findOneBy([
                'estudiante_id' => $alumno,
                'tecnicatura_id' => $tecnicatura,
            ]);
        
            if ($carreraExistente) {
                // Si ya existe, mostrar un mensaje de error y redirigir
                $this->addFlash('success', 'El alumno ya está inscrito en esta carrera.');
                return $this->redirectToRoute('app_vistaspreinscriptos', [
                ], Response::HTTP_SEE_OTHER);
            }
    
            // Si no existe, guardar la nueva carrera
            $carrerasRepository->save($carreras, true);

            // Después de guardar en la DB, actualizar el CSV
            $filename = 'preinscripciones_instituto_copy.csv'; 
            $filePath = '../public/preinscriptos/' . $filename;
            $data = $this->preinscriptionService->readCsvCopy($filePath);
            // Encontrar el registro en el CSV que coincide con el DNI
            foreach ($data as &$entry) {
                if ($entry['id'] == $id) { 
                    $entry['new_field'] = '3'; // Actualizar el campo con el valor '3'
                    break;
                }
            }
            // Guardar los cambios en el archivo CSV
            $this->preinscriptionService->updateCsv($filePath, $data); 

            // Mostrar un mensaje de éxito y redirigir
            $this->addFlash('success', '¡Entrada Creada con Éxito!');
            return $this->redirectToRoute('app_vistaspreinscriptos', [], Response::HTTP_SEE_OTHER);
        }          
        return $this->renderForm('vistaspreinscriptos/create_carrera_pre.html.twig', [
            'id' => $id,
            'dni' => $dni,
            'carreras' => $carreras,
            'form' => $form,
        ]);
    }



    //SI LA CARRERA EXISTE ENTRA A ESTA FUNCION PARA PROSEGUIR LA SECUENACIA!!!!!!
    #[Route('/existeCarrera/{dni}', name: 'carrera_siguiente', methods: ['GET', 'POST'])]
    public function siguienteCarrera(Request $request, string $dni, InstitutoRepository $institutoRepository, PersonaRepository $personaRepository, AlumnoRepository $alumnoRepository, CarrerasRepository $carrerasRepository): Response
    {
        // Recuperar la sesión
        $session = $request->getSession();    
        // Limpiar cualquier valor previo en la sesión relacionado con el DNI
        //$session->remove('dni');  
        // Verificar si el DNI no tiene el prefijo '1.' para evitar duplicados
        if ($dni !== null && !str_starts_with($dni, '3.')) {
            $session->set('dni', '3.' . $dni);
        } else {
            // Si ya tiene el prefijo, lo guardamos tal cual
            $session->set('dni', $dni);
        }
        // Obtener el valor del DNI desde la sesión
        $dni = $session->get('dni');            
        //return $this->redirectToRoute('app_vistaspreinscriptos', [], Response::HTTP_SEE_OTHER);
      
        // Obtener el CUE del instituto actual
        $instituto = $institutoRepository->findOneBy(['id' => 1]);
        $numeroCue = $instituto ? $instituto->getNumeroCue() : null;
        
        // Generar el CSV automáticamente si se tiene el CUE
        if ($numeroCue) {
            $data = $this->preinscriptionService->fetchPreinscriptionDataByInstitute($numeroCue);
            $filename = $this->preinscriptionService->generateCsv($data, 'preinscripciones_instituto');
        }
        
        $filename = 'preinscripciones_instituto.csv';
        $filePath = '../public/preinscriptos/' . $filename;
        $data = null;
        
        if (file_exists($filePath) && $this->isValidCsv($filePath)) {
            $data = $this->preinscriptionService->readCsv($filePath);
        }
        
        // Automáticamente hacer la copia y modificar el CSV
        $filename2 = 'preinscripciones_instituto_copy.csv';
        $filePath2 = '../public/preinscriptos/' . $filename2;
        $data2 = null;
        
        if (file_exists($filePath)) {
            $filePath2 = $this->preinscriptionService->copyAndAddField($filePath);
        }
        
        if (file_exists($filePath2) && $this->isValidCsv($filePath2)) {
            $data2 = $this->preinscriptionService->readCsvCopy($filePath2);
        }

        // Serializar solo los DNIs en un array
        $personas = $personaRepository->findAll();
        $dniList = array_map(function($persona) {
            return $persona->getDniPasaporte();
        }, $personas);

        // Serializar solo los DNIs en un array
        $alumnos = $alumnoRepository->findAll();
        // Crear un array de DNIs de personas asociadas a los alumnos
        $dniListAlu = array_map(function($alumno) {
            // Obtener la persona asociada al alumno y su DNI
            $persona = $alumno->getPersona();
            return $persona ? $persona->getDniPasaporte() : null;
        }, $alumnos);

        // Serializar los DNIs junto con las carreras en un array
        $carreras = $carrerasRepository->findAll();
        $dniListCar = array_map(function($carrera) {
            // Obtener el alumno asociado a la carrera
            $alumno = $carrera->getEstudianteId();
            // Obtener la persona asociada al alumno y su DNI
            $persona = $alumno ? $alumno->getPersona() : null;
            $tecnicatura = $carrera->getTecnicaturaId();
            return $persona ? [
                'dni' => $persona->getDniPasaporte(),
                'tecnicatura' => $tecnicatura ? $tecnicatura->getNumeroResolucion() : null
            ] : null;
        }, $carreras);
        // Filtrar para eliminar valores nulos
        $dniListCar = array_filter($dniListCar);


        return $this->render('vistaspreinscriptos/index.html.twig', [
            'dni' => $dni,
            'copied_data' => $data2,
            'data' => $data,
            'filename' => $filename,
            'path' => $filePath,
            'institutos' => $institutoRepository->findAll(),
            'personas' => json_encode($dniList),
            'alumnos' => json_encode($dniListAlu),
            'carreras' => json_encode($dniListCar),
        ]);
    }



        

    //EDIT2 SOLO SE USA PARA AUTOMATIZAR EL ENVIO DEL CHECK EN UNA INCORPORACION AUTOMATICA DE PREINSCRIPCION A EL INSTITUTO
    #[Route('/vistaspreinscriptos/edit2/{id}', name: 'app_vistaspreinscriptos_edit2', methods: ['GET', 'POST'])]
    public function edit2(int $id, Request $request): Response
    {
        $filename = 'preinscripciones_instituto_copy.csv'; 
        $filePath = '../public/preinscriptos/' . $filename;
        $data = $this->preinscriptionService->readCsvCopy($filePath);
        // Encuentra el registro específico por ID
        $recordToUpdate = null;
        foreach ($data as &$entry) {
            if ($entry['id'] == $id) {
                $recordToUpdate = &$entry;
                break;
            }
        }
        if (!$recordToUpdate) {
            throw $this->createNotFoundException('Registro no encontrado.');
        }
        if ($request->isMethod('POST')) {
            $recordToUpdate['new_field'] = $request->request->get('new_field', $recordToUpdate['new_field']);

            // Actualiza el archivo CSV con el registro modificado
            $this->preinscriptionService->updateCsv($filePath, $data);
            
            return $this->redirectToRoute('app_vistaspreinscriptos');
        }
        return $this->render('vistaspreinscriptos/edit2.html.twig', [
            'record' => $recordToUpdate,
        ]);
    }


    //EDIT3 SOLO SE USA PARA AUTOMATIZAR EL ENVIO DEL CHECK EN UNA INCORPORACION AUTOMATICA DE PREINSCRIPCION A EL INSTITUTO
    #[Route('/vistaspreinscriptos/edit3/{id}', name: 'app_vistaspreinscriptos_edit3', methods: ['GET', 'POST'])]
    public function edit3(int $id, Request $request): Response
    {
        $filename = 'preinscripciones_instituto_copy.csv'; 
        $filePath = '../public/preinscriptos/' . $filename;
        $data = $this->preinscriptionService->readCsvCopy($filePath);
        // Encuentra el registro específico por ID
        $recordToUpdate = null;
        foreach ($data as &$entry) {
            if ($entry['id'] == $id) {
                $recordToUpdate = &$entry;
                break;
            }
        }
        if (!$recordToUpdate) {
            throw $this->createNotFoundException('Registro no encontrado.');
        }
        if ($request->isMethod('POST')) {
            $recordToUpdate['new_field'] = $request->request->get('new_field', $recordToUpdate['new_field']);   
            // Actualiza el archivo CSV con el registro modificado
            $this->preinscriptionService->updateCsv($filePath, $data);                
            return $this->redirectToRoute('app_vistaspreinscriptos');
        }
        return $this->render('vistaspreinscriptos/edit3.html.twig', [
            'record' => $recordToUpdate,
        ]);
    }


    //EDIT4 SOLO SE USA PARA AUTOMATIZAR EL ENVIO DEL CHECK EN UNA INCORPORACION AUTOMATICA DE PREINSCRIPCION A EL INSTITUTO
    #[Route('/vistaspreinscriptos/edit4/{id}', name: 'app_vistaspreinscriptos_edit4', methods: ['GET', 'POST'])]
    public function edit4(int $id, Request $request): Response
    {
        $filename = 'preinscripciones_instituto_copy.csv'; 
        $filePath = '../public/preinscriptos/' . $filename;
        $data = $this->preinscriptionService->readCsvCopy($filePath);
        // Encuentra el registro específico por ID
        $recordToUpdate = null;
        foreach ($data as &$entry) {
            if ($entry['id'] == $id) {
                $recordToUpdate = &$entry;
                break;
            }
        }
        if (!$recordToUpdate) {
            throw $this->createNotFoundException('Registro no encontrado.');
        }
        if ($request->isMethod('POST')) {
            // Actualiza el registro con los datos del formulario  -Revisar si se llegan a necesitar mas edicion modificar aqui tambien-
            $recordToUpdate['new_field'] = $request->request->get('new_field', $recordToUpdate['new_field']);

            // Actualiza el archivo CSV con el registro modificado
            $this->preinscriptionService->updateCsv($filePath, $data);
            
            return $this->redirectToRoute('app_vistaspreinscriptos');
        }
        return $this->render('vistaspreinscriptos/edit4.html.twig', [
            'record' => $recordToUpdate,
        ]);
    }


    //EDIT5 SOLO SE USA PARA AUTOMATIZAR EL ENVIO DEL CHECK EN UNA INCORPORACION AUTOMATICA DE PREINSCRIPCION A EL INSTITUTO
    #[Route('/vistaspreinscriptos/edit5/{id}', name: 'app_vistaspreinscriptos_edit5', methods: ['GET', 'POST'])]
    public function edit5(int $id, Request $request): Response
    {
        $filename = 'preinscripciones_instituto_copy.csv'; 
        $filePath = '../public/preinscriptos/' . $filename;
        $data = $this->preinscriptionService->readCsvCopy($filePath);
        // Encuentra el registro específico por ID
        $recordToUpdate = null;
        foreach ($data as &$entry) {
            if ($entry['id'] == $id) {
                $recordToUpdate = &$entry;
                break;
            }
        }
        if (!$recordToUpdate) {
            throw $this->createNotFoundException('Registro no encontrado.');
        }
        if ($request->isMethod('POST')) {
            // Actualiza el registro con los datos del formulario  -Revisar si se llegan a necesitar mas edicion modificar aqui tambien-
            $recordToUpdate['new_field'] = $request->request->get('new_field', $recordToUpdate['new_field']);
    
            // Actualiza el archivo CSV con el registro modificado
            $this->preinscriptionService->updateCsv($filePath, $data);
                
            return $this->redirectToRoute('app_vistaspreinscriptos');
        }
        return $this->render('vistaspreinscriptos/edit5.html.twig', [
            'record' => $recordToUpdate,
        ]);
    }

}
