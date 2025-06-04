<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Form\PersonaType;
use App\Repository\PersonaRepository;
use App\Entity\Alumno;
use App\Form\AlumnoType;
use App\Repository\AlumnoRepository;
use App\Entity\Carreras;
use App\Form\CarrerasType;
use App\Repository\CarrerasRepository;

use App\Repository\InstitutoRepository;
use App\Service\PreinscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;   //para captar campos unicos que queremos duplicar y la base no nos deja por consecuencia

class VistaspreinscriptosController extends AbstractController
{
    private $preinscriptionService;

    public function __construct(PreinscriptionService $preinscriptionService)
    {
        $this->preinscriptionService = $preinscriptionService;
    }

    //INDEX
    #[Route('/vistaspreinscriptos', name: 'app_vistaspreinscriptos')]
    public function index(InstitutoRepository $institutoRepository, PersonaRepository $personaRepository, AlumnoRepository $alumnoRepository, Request $request): Response
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


    #[Route('/vistaspreinscriptos/edit/{id}', name: 'app_vistaspreinscriptos_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
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
            'record' => $recordToUpdate,
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

/*
    //CREAR PERSONA!!!!!!
    #[Route('/nuevapersonaPre/{dni}', name: 'crear_persona_pre', methods: ['GET', 'POST'])]
    public function nuevapersonaPre(Request $request, PersonaRepository $personaRepository, string $dni): Response
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

        //captando posible dni duplicado en la dbs
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $personaRepository->save($persona, true);
                return $this->redirectToRoute('app_vistaspreinscriptos', [], Response::HTTP_SEE_OTHER);
            } catch (UniqueConstraintViolationException $e) {
                // Manejar el caso de DNI duplicado
                $session->remove('dni'); // Limpiar el DNI de la sesión
                //$this->addFlash('errorDNI', 'Entrada repetida de DNI');
            } catch (\Exception $e) {
                $session->remove('dni'); // Limpiar el DNI de la sesión
                $this->addFlash('errorDNI', 'Ocurrió un error al guardar la persona');
            }
        }
        return $this->renderForm('vistaspreinscriptos/create_persona_form.html.twig', [
            'dni' => $dni,
            'persona' => $persona,
            'form' => $form,
        ]);
    }
*/
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
                if ($entry['id'] == $id) { // Supongo que tienes un campo 'dni' en el CSV
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
        public function siguientePersonaPer(Request $request, string $dni, InstitutoRepository $institutoRepository, PersonaRepository $personaRepository, AlumnoRepository $alumnoRepository): Response
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


            return $this->render('vistaspreinscriptos/index.html.twig', [
                'dni' => $dni,
                'copied_data' => $data2,
                'data' => $data,
                'filename' => $filename,
                'path' => $filePath,
                'institutos' => $institutoRepository->findAll(),
                'personas' => json_encode($dniList),
                'alumnos' => json_encode($dniListAlu),
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
                if ($entry['id'] == $id) { // Supongo que tienes un campo 'dni' en el CSV
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
        public function siguienteAlumno(Request $request, string $dni, InstitutoRepository $institutoRepository, PersonaRepository $personaRepository, AlumnoRepository $alumnoRepository): Response
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


            return $this->render('vistaspreinscriptos/index.html.twig', [
                'dni' => $dni,
                'copied_data' => $data2,
                'data' => $data,
                'filename' => $filename,
                'path' => $filePath,
                'institutos' => $institutoRepository->findAll(),
                'personas' => json_encode($dniList),
                'alumnos' => json_encode($dniListAlu),
            ]);
        }



        #[Route('/nueva/carreras/{id}', name: 'crear_carrera_Pre', methods: ['GET', 'POST'])]
        public function nuevacarreraPreinscripcion(Request $request, CarrerasRepository $carrerasRepository, int $id): Response
        {     
            // Recuperar la sesión
            $session = $request->getSession();
            // Limpiar cualquier valor previo en la sesión relacionado con el DNI
            $session->remove('dni');
            
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
                    return $this->redirectToRoute('app_vistaspreinscriptos', [], Response::HTTP_SEE_OTHER);
                }

        
                // Si no existe, guardar la nueva carrera
                $carrerasRepository->save($carreras, true);

                // Después de guardar en la DB, actualizar el CSV
                $filename = 'preinscripciones_instituto_copy.csv'; 
                $filePath = '../public/preinscriptos/' . $filename;
                $data = $this->preinscriptionService->readCsvCopy($filePath);
                // Encontrar el registro en el CSV que coincide con el DNI
                foreach ($data as &$entry) {
                    if ($entry['id'] == $id) { // Supongo que tienes un campo 'dni' en el CSV
                        $entry['new_field'] = '3'; // Actualizar el campo con el valor '1'
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
                'carreras' => $carreras,
                'form' => $form,
            ]);
        }
        



   /* //CREAR CARRERA AL ALUMNO (TECNICATURAS)
    #[Route('/nueva/carreras', name: 'crear_carrera_Pre', methods: ['GET', 'POST'])]
    public function nuevacarreraPreinscripcion(Request $request, CarrerasRepository $carrerasRepository): Response
    {     
        // Recuperar la sesión
        $session = $request->getSession();
        // Limpiar cualquier valor previo en la sesión relacionado con el DNI
        $session->remove('dni');
        $carreras = new Carreras();
        $form = $this->createForm(CarrerasType::class, $carreras);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $carrerasRepository->save($carreras, true);

            $this->addFlash('success', '¡Entrada Creada con Exito!');    
            return $this->redirectToRoute('app_vistaspreinscriptos', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('vistaspreinscriptos/create_carrera_pre.html.twig', [
            'carreras' => $carreras,
            'form' => $form,
        ]);
    }*/



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

}
