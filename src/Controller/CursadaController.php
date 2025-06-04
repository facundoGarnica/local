<?php

namespace App\Controller;

use App\Entity\Nota;
use App\Entity\Cursada;
use App\Form\CursadaType;
use App\Repository\CursadaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/cursada')]
class CursadaController extends AbstractController
{
    #[Route('/', name: 'app_cursada_index', methods: ['GET'])]
    public function index(CursadaRepository $cursadaRepository): Response
    {
        return $this->render('cursada/index.html.twig', [
            'cursadas' => $cursadaRepository->findAll(),
        ]);
    }

   /* #[Route('/new', name: 'app_cursada_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CursadaRepository $cursadaRepository): Response
    {
        $cursada = new Cursada();
        $form = $this->createForm(CursadaType::class, $cursada);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cursadaRepository->save($cursada, true);

            return $this->redirectToRoute('app_cursada_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cursada/new.html.twig', [
            'cursada' => $cursada,
            'form' => $form,
        ]);
    }
*/
#[Route('/new', name: 'app_cursada_new', methods: ['GET', 'POST'])]
public function new(Request $request, CursadaRepository $cursadaRepository): Response
{
    $cursada = new Cursada();
    $form = $this->createForm(CursadaType::class, $cursada);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Crear una nueva instancia de Nota
        $nota = new Nota();
        // Asignar valores iniciales a la Nota si es necesario
        $nota->setParcial(''); // o cualquier valor predeterminado
        $nota->setRecuperatorio1('');
        $nota->setParcial2('');
        $nota->setRecuperatorio2('');

        // Asignar la nota creada a la cursada
        $cursada->setNotaId($nota);

        // Guardar la cursada (y la nota asociada debido a la relación en cascada)
        $cursadaRepository->save($cursada, true);

        return $this->redirectToRoute('app_cursada_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('cursada/new.html.twig', [
        'cursada' => $cursada,
        'form' => $form,
    ]);
}



    #[Route('/{id}', name: 'app_cursada_show', methods: ['GET'])]
    public function show(Cursada $cursada): Response
    {
        return $this->render('cursada/show.html.twig', [
            'cursada' => $cursada,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cursada_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cursada $cursada, CursadaRepository $cursadaRepository): Response
    {
        $form = $this->createForm(CursadaType::class, $cursada);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cursadaRepository->save($cursada, true);

            return $this->redirectToRoute('app_cursada_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cursada/edit.html.twig', [
            'cursada' => $cursada,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cursada_delete', methods: ['POST'])]
    public function delete(Request $request, Cursada $cursada, CursadaRepository $cursadaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cursada->getId(), $request->request->get('_token'))) {
            $cursadaRepository->remove($cursada, true);
        }

        return $this->redirectToRoute('app_cursada_index', [], Response::HTTP_SEE_OTHER);
    }
}
