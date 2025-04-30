<?php

namespace App\Controller;

use App\Entity\Reto;
use App\Form\RetoType;
use App\Repository\RetoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reto')]
final class RetoController extends AbstractController
{
    #[Route(name: 'app_reto_index', methods: ['GET'])]
    public function index(RetoRepository $retoRepository): Response
    {
        return $this->render('reto/index.html.twig', [
            'retos' => $retoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reto = new Reto();
        $form = $this->createForm(RetoType::class, $reto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reto);
            $entityManager->flush();

            return $this->redirectToRoute('app_reto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reto/new.html.twig', [
            'reto' => $reto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reto_show', methods: ['GET'])]
    public function show(Reto $reto): Response
    {
        return $this->render('reto/show.html.twig', [
            'reto' => $reto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reto $reto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RetoType::class, $reto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reto/edit.html.twig', [
            'reto' => $reto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reto_delete', methods: ['POST'])]
    public function delete(Request $request, Reto $reto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reto->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reto_index', [], Response::HTTP_SEE_OTHER);
    }
}
