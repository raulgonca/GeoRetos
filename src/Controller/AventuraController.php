<?php

namespace App\Controller;

use App\Entity\Aventura;
use App\Form\AventuraType;
use App\Repository\AventuraRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/aventura')]
final class AventuraController extends AbstractController
{
    #[Route(name: 'app_aventura_index', methods: ['GET'])]
    public function index(AventuraRepository $aventuraRepository): Response
    {
        return $this->render('aventura/index.html.twig', [
            'aventuras' => $aventuraRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_aventura_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Decodificar el contenido JSON de la solicitud
        $data = json_decode($request->getContent(), true);
        
        if (!$data) {
            return $this->json(['error' => 'Datos JSON inválidos'], Response::HTTP_BAD_REQUEST);
        }
        
        // Crear una nueva aventura
        $aventura = new Aventura();
        
        // Configurar los campos de la aventura desde los datos JSON
        if (isset($data['titulo'])) {
            $aventura->setTitulo($data['titulo']);
        }
        
        if (isset($data['descripcion'])) {
            $aventura->setDescripcion($data['descripcion']);
        }
        
        // Manejar la imagen (si se proporciona como base64)
        if (isset($data['imagen_base64'])) {
            // Decodificar la imagen base64
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['imagen_base64']));
            
            // Generar un nombre único para la imagen
            $filename = uniqid() . '.jpg';
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/aventuras/';
            
            // Guardar la imagen en el servidor
                file_put_contents($uploadDir . $filename, $imageData);
            
            // Guardar la ruta relativa en la base de datos
            $aventura->setImagenPortada('/uploads/aventuras/' . $filename);
        } else if (isset($data['imagen'])) {
            // Si solo se proporciona una URL o ruta
            $aventura->setImagenPortada($data['imagen']);
        }
        
        // Establecer la fecha de creación
        $aventura->setCreadoEn(new \DateTimeImmutable());
        
        // Guardar la aventura en la base de datos
        $entityManager->persist($aventura);
        $entityManager->flush();
        
        // Devolver la aventura creada como respuesta JSON
        return $this->json([
            'message' => 'Aventura creada con éxito',
            'aventura' => [
                'id' => $aventura->getId(),
                'titulo' => $aventura->getTitulo(),
                'descripcion' => $aventura->getDescripcion(),
                'imagen_portada' => $aventura->getImagenPortada(),
                'creado_en' => $aventura->getCreadoEn()->format('Y-m-d H:i:s')
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/all', name: 'app_aventura_all', methods: ['GET'])]
    public function getAllAventuras(AventuraRepository $aventuraRepository): JsonResponse
    {
        $aventuras = $aventuraRepository->findAll();
        $aventurasData = [];
        
        foreach ($aventuras as $aventura) {
            $aventurasData[] = [
                'id' => $aventura->getId(),
                'titulo' => $aventura->getTitulo(),
                'descripcion' => $aventura->getDescripcion(),
                'imagen_portada' => $aventura->getImagenPortada(),
                'numero_de_retos' => $aventura->getNumeroDeRetos(),
                'creado_en' => $aventura->getCreadoEn()->format('Y-m-d H:i:s'),
                'actualizado_en' => $aventura->getActualizadoEn()
            ];
        }
        
        return $this->json($aventurasData);
    }

    #[Route('/{id}', name: 'app_aventura_show', methods: ['GET'])]
    public function show(Aventura $aventura): Response
    {
        return $this->render('aventura/show.html.twig', [
            'aventura' => $aventura,
        ]);
    }

   
    #[Route('/{id}', name: 'app_aventura_delete', methods: ['POST'])]
    public function delete(Request $request, Aventura $aventura, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($aventura);
        $entityManager->flush();
        
        return $this->json(['message' => 'Aventura eliminada con éxito']);
    }
}
