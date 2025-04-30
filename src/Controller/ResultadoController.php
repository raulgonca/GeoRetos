<?php

namespace App\Controller;

use App\Entity\Resultado;
use App\Form\ResultadoType;
use App\Repository\ResultadoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/resultado')]
final class ResultadoController extends AbstractController
{
    #[Route(name: 'app_resultado_index', methods: ['GET'])]
    public function index(ResultadoRepository $resultadoRepository): Response
    {
        return $this->render('resultado/index.html.twig', [
            'resultados' => $resultadoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_resultado_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $resultado = new Resultado();
        $form = $this->createForm(ResultadoType::class, $resultado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($resultado);
            $entityManager->flush();

            return $this->redirectToRoute('app_resultado_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('resultado/new.html.twig', [
            'resultado' => $resultado,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_resultado_show', methods: ['GET'])]
    public function show(Resultado $resultado): Response
    {
        return $this->render('resultado/show.html.twig', [
            'resultado' => $resultado,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_resultado_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Resultado $resultado, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResultadoType::class, $resultado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_resultado_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('resultado/edit.html.twig', [
            'resultado' => $resultado,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_resultado_delete', methods: ['POST'])]
    public function delete(Request $request, Resultado $resultado, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resultado->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($resultado);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_resultado_index', [], Response::HTTP_SEE_OTHER);
    }
}
