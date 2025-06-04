<?php

namespace App\Controller;

use App\Entity\Correlativa;
use App\Form\CorrelativaType;
use App\Entity\Asignatura;
use App\Form\AsignaturaType;
use App\Repository\AsignaturaRepository;
use App\Entity\Tecnicatura;
use App\Form\TecnicaturaType;
use App\Repository\TecnicaturaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class VistasController extends AbstractController
{
    #[Route('/vistas', name: 'app_vistas')]
    public function index(TecnicaturaRepository $tecnicaturaRepository, AsignaturaRepository $asignaturaRepository, Request $request): Response
    {
        $session = $request->getSession();
        $tecId = $session->get('tecId', null);
        $session->remove('tecId');
        if ($tecId === null) {
            $tecId = null; 
        } else {
            $session->remove('tecId');
        }

        return $this->render('vistas/index.html.twig', [
            'tecnicaturas' => $tecnicaturaRepository->findAll(),
            'asignaturas' => $asignaturaRepository->findAll(),
            'tecId' => $tecId,
        ]);
    }

 
    #[Route('/vistas/{id}/tecnicatura/{tecnicatura_id?}', name: 'editar_asignatura')]
    public function edit(Request $request, Asignatura $asignatura, AsignaturaRepository $asignaturaRepository, TecnicaturaRepository $tecnicaturaRepository): Response
    {
        $tecnicatura_id = $request->attributes->get('tecnicatura_id', null);
    
        // Almacenar `tecId` en la sesión
        $session = $request->getSession();
        if ($tecnicatura_id !== null) {
            $session->set('tecId', $tecnicatura_id);
        }
    
        // Depuración borrar cuando este todo vistas listo!
        if ($tecnicatura_id === null) {
            //$this->addFlash('error', 'El ID de la tecnicatura no se recibió.'); //--este mensaje sobra
        } else {
            $this->addFlash('success', "Valor de tecnicatura_id: $tecnicatura_id"); // sin la sesion el valor se termina perdiendo entre pases de vistas y controladores.. al retornar el valor
        }
        dump($tecnicatura_id);
    
        $form = $this->createForm(AsignaturaType::class, $asignatura);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $asignaturaRepository->save($asignatura, true);
    
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Asignatura actualizada correctamente.',
                ]);
            }
    
            // Redirigir a la vista `index` con todos los datos
            return $this->redirectToRoute('app_vistas');
        }
    
        if ($request->isXmlHttpRequest()) {
            return $this->render('vistas/edit_form.html.twig', [
                'asignatura' => $asignatura,
                'form' => $form->createView(),
                'tecId' => $tecnicatura_id,
            ]);
        }
    
        return $this->renderForm('vistas/edit_form.html.twig', [
            'asignatura' => $asignatura,
            'form' => $form,
            'tecId' => $tecnicatura_id,
        ]);
    }
    
    
    #[Route('/tecnicat/{tecnicatura_id?}', name: 'crear_asignatura', methods: ['GET', 'POST'])]
    public function create(Request $request, AsignaturaRepository $asignaturaRepository): Response
    {
        $tecnicatura_id = $request->attributes->get('tecnicatura_id', null);
    
        // Almacenar `tecId` en la sesión
        $session = $request->getSession();
        if ($tecnicatura_id !== null) {
            $session->set('tecId', $tecnicatura_id);
        }

        $tecId = $session->get('tecId', null);    

        $asignatura = new Asignatura();
        $form = $this->createForm(AsignaturaType::class, $asignatura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $asignaturaRepository->save($asignatura, true);
    
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Asignatura actualizada correctamente.',
                ]);
            }
    
            // Redirigir a la vista `index` con todos los datos
            return $this->redirectToRoute('app_vistas');
        }
    
        if ($request->isXmlHttpRequest()) {
            return $this->render('vistas/create_form.html.twig', [
                'asignatura' => $asignatura,
                'form' => $form->createView(),
                'tecId' => $tecId,
            ]);
        }
    
        return $this->renderForm('vistas/create_form.html.twig', [
            'asignatura' => $asignatura,
            'form' => $form,
            'tecId' => $tecId,
        ]);
    }



    
    #[Route('/nuevatecnicatura', name: 'crear_tecnicatura', methods: ['GET', 'POST'])]
    public function create2(Request $request, TecnicaturaRepository $tecnicaturaRepository): Response
    {
        $tecnicatura = new Tecnicatura();
        $form = $this->createForm(TecnicaturaType::class, $tecnicatura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tecnicaturaRepository->save($tecnicatura, true);

            // Devolver una respuesta JSON si es una petición AJAX
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Tecnicatura creada correctamente.',
                ]);
            }
            // Redireccionar a otra página si no es una petición AJAX
            return $this->redirectToRoute('app_vistas', [], Response::HTTP_SEE_OTHER);
        }
        // Renderizar el formulario de edición si es una petición AJAX
        if ($request->isXmlHttpRequest()) {
            return $this->render('vistas/create2_form.html.twig', [
                'tecnicatura' => $tecnicatura,
                'form' => $form->createView(),
            ]);
        }
        // Renderizar el formulario normal si no es una petición AJAX
        return $this->renderForm('vistas/create2_form.html.twig', [
            'tecnicatura' => $tecnicatura,
            'form' => $form,
        ]);
    }

    #[Route('/upload-pdf', name: 'upload_pdf', methods: ['POST'])] // Cambiado a POST para la subida de archivos
    public function uploadPdf(Request $request, TecnicaturaRepository $tecnicaturaRepository, AsignaturaRepository $asignaturaRepository,): Response
    {
        $tecnicaturaId = $request->request->get('tecnicatura_id');
        $pdfFile = $request->files->get('pdf_file');

        if ($pdfFile && $pdfFile->isValid()) {
            // Define el directorio de destino
            $destination = $this->getParameter('kernel.project_dir').'/public/archivos/tecnicaturas';

            // Define el nuevo nombre del archivo usando el ID de la tecnicatura
            $newFilename = $tecnicaturaId . '.pdf';

            try {
                // Mueve el archivo al directorio de destino
                $pdfFile->move($destination, $newFilename);

                // Almacenar `tecId` en la sesión si es necesario
                $session = $request->getSession();
                if ($tecnicaturaId !== null) {
                    $session->set('tecId', $tecnicaturaId);
                }
                $this->addFlash('success', 'El archivo ha sido subido exitosamente.');
                $tecId = $session->get('tecId', null);
                $tecnicaturas = $tecnicaturaRepository->findAll();


                return $this->render('vistas/index.html.twig', [
                    'tecnicaturas' => $tecnicaturas,
                    'asignaturas' => $asignaturaRepository->findAll(),
                    'tecId' => $tecId,
                ]);
            } catch (FileException $e) {
                $this->addFlash('error', 'Hubo un error al subir el archivo.');
            }
        }
        return $this->redirectToRoute('app_vistas');
    }

    #[Route('/asignatura-pdf', name: 'asignatura_pdf', methods: ['POST'])]
    public function asignaturasPdf(Request $request, TecnicaturaRepository $tecnicaturaRepository, AsignaturaRepository $asignaturaRepository): Response
    {
        $asignaturaId = $request->request->get('asignatura_id');
        $tecnicaturaId = $request->request->get('tecnicatura_id'); // Recupera el ID de la tecnicatura

        $pdfFile = $request->files->get('pdf_file');

        if ($pdfFile && $pdfFile->isValid()) {
            // Define el directorio de destino
            $destination = $this->getParameter('kernel.project_dir') . '/public/archivos/asignaturas';

            // Define el nuevo nombre del archivo usando el ID de la asignatura
            $newFilename = $asignaturaId . '.pdf';

            try {
                // Mueve el archivo al directorio de destino
                $pdfFile->move($destination, $newFilename);

                // Almacenar `tecId` en la sesión si es necesario
                $session = $request->getSession();
                if ($tecnicaturaId !== null) {
                    $session->set('tecId', $tecnicaturaId);
                }

                // Redirige o muestra un mensaje de éxito
                $this->addFlash('success', 'El archivo ha sido subido exitosamente.');

                // Recuperar `tecId` de la sesión
                $tecId = $session->get('tecId', null);

                // Renderizar la vista `app_vistas` con las variables necesarias
                return $this->render('vistas/index.html.twig', [
                    'tecnicaturas' => $tecnicaturaRepository->findAll(),
                    'asignaturas' => $asignaturaRepository->findAll(),
                    'tecId' => $tecId,
                ]);
            } catch (FileException $e) {
                // Maneja la excepción en caso de error al mover el archivo
                $this->addFlash('error', 'Hubo un error al subir el archivo.');
            }
        }

        // Redirige a la vista `app_vistas` si no se subió ningún archivo válido o ocurrió un error
        return $this->redirectToRoute('app_vistas');
    }

    #[Route('/api/pdf-list', name: 'pdf_list')]
    public function list(): JsonResponse
    {
        $pdfPaths = [
            'tecnicaturas' => $this->getParameter('kernel.project_dir').'/public/archivos/tecnicaturas',
            'asignaturas' => $this->getParameter('kernel.project_dir').'/public/archivos/asignaturas'
        ];

        $pdfFiles = [];

        foreach ($pdfPaths as $type => $path) {
            if (!is_dir($path)) {
                continue; // Si el directorio no existe, pasa al siguiente
            }

            $files = array_diff(scandir($path), ['.', '..']);
            $pdfFiles[$type] = array_filter($files, function($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
            });
        }

        return new JsonResponse($pdfFiles);
    }

    #[Route('/addcorrelativa/{asignatura_id}/otro/{tecnicatura_id}', name: 'add_correlativa', methods: ['GET', 'POST'])]
    public function addCorrelativa(Request $request, $asignatura_id, $tecnicatura_id, AsignaturaRepository $asignaturaRepository, TecnicaturaRepository $tecnicaturaRepository): Response
    {

        $tecnicatura_id = $request->attributes->get('tecnicatura_id', null);
    
        // Almacenar `tecId` en la sesión
        $session = $request->getSession();
        if ($tecnicatura_id !== null) {
            $session->set('tecId', $tecnicatura_id);
        }

        $tecId = $session->get('tecId', null);  

        $correlativa = new Correlativa();
        $form = $this->createForm(CorrelativaType::class, $correlativa);
        $form->handleRequest($request);

        // Obtener la asignatura y tecnicatura correspondientes
        $asignatura = $asignaturaRepository->find($asignatura_id);
        $tecnicatura = $tecnicaturaRepository->find($tecnicatura_id);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Asignar la asignatura a la correlativa
            $correlativa->setAsignatura($asignatura);

            // Persistir la correlativa
            $entityManager->persist($correlativa);
            $entityManager->flush();

            // Agregar la correlativa a la asignatura
            $asignatura->addCorrelativa($correlativa);
            $entityManager->persist($asignatura);
            $entityManager->flush();

            $this->addFlash('success', 'Correlativa agregada correctamente.');

            return $this->redirectToRoute('app_vistas'); 
        }

        return $this->render('vistas/create3_form.html.twig', [
            'correlativa' => $correlativa,
            'form' => $form->createView(),
            'asignatura' => $asignatura,  
            'tecnicatura' => $tecnicatura, 
            'tecId' => $tecId
        ]);
    }
    



    //Para ver las correlativas de una asignatura seleccionada
    #[Route('/viewcorrelativas/{asignatura_id}', name: 'view_correlativas', methods: ['GET'])]
    public function viewCorrelativas($asignatura_id, AsignaturaRepository $asignaturaRepository): Response
    {
        $asignatura = $asignaturaRepository->find($asignatura_id);
        if (!$asignatura) {
            throw $this->createNotFoundException('Asignatura no encontrada');
        }
    
        return $this->render('vistas/view_correlativas.html.twig', [
            'asignatura' => $asignatura,
            'correlativas' => $asignatura->getCorrelativas(),
        ]);
    }

    

}

