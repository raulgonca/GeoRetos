<?php

namespace App\Controller;

use App\Entity\Reto;
use App\Repository\RetoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/reto')]
final class RetoController extends AbstractController
{
    /**
     * Crea un nuevo reto a través de la API
     */
    #[Route('/api/new', name: 'api_reto_new', methods: ['POST'])]
    public function newReto(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!$data) {
            return new JsonResponse(['error' => 'Datos inválidos'], Response::HTTP_BAD_REQUEST);
        }
        
        $reto = new Reto();
        
        // Establecer valores para todos los campos, usando los datos proporcionados o valores predeterminados
        $reto->setTitulo($data['nombre'] ?? 'Sin título');
        $reto->setDescripcion($data['descripcion'] ?? 'Sin descripción');
        $reto->setInstrucciones($data['instrucciones'] ?? 'Sin instrucciones');
        $reto->setTipoReto($data['tipo_reto'] ?? 'quiz'); // Valor predeterminado: quiz
        $reto->setImagenReto($data['imagen_reto'] ?? null);
        $reto->setRespuestas($data['respuestas'] ?? []); // Array vacío por defecto
        $reto->setRespuestaCorrecta($data['respuesta_correcta'] ?? '');
        $reto->setLatitud($data['latitud'] ?? 0);
        $reto->setLongitud($data['longitud'] ?? 0);
        $reto->setMargenErrorMetros($data['margen_error_metros'] ?? 10); // 10 metros por defecto
        $reto->setPuntosFallo0($data['puntos_fallo_0'] ?? 100); // 100 puntos por defecto
        $reto->setPuntosFallo1($data['puntos_fallo_1'] ?? 75); // 75 puntos por defecto
        $reto->setPuntosFallo2($data['puntos_fallo_2'] ?? 50); // 50 puntos por defecto
        $reto->setPuntosFallo3($data['puntos_fallo_3'] ?? 25); // 25 puntos por defecto
        $reto->setEsObligatorioSuperar($data['es_obligatorio_superar'] ?? false);
        
        // Establecer fechas de creación y actualización
        $now = new \DateTime();
        $reto->setCreado($now);
        $reto->setActualizado($now);
        
        // Si se proporciona un ID de aventura, asociar el reto con esa aventura
        if (isset($data['id_aventura'])) {
            $aventuraRepository = $entityManager->getRepository('App\Entity\Aventura');
            $aventura = $aventuraRepository->find($data['id_aventura']);
            
            if ($aventura) {
                $reto->setIdAventura($aventura);
            }
        }
        
        try {
            $entityManager->persist($reto);
            $entityManager->flush();
            
            return new JsonResponse([
                'message' => 'Reto creado correctamente',
                'id' => $reto->getId()
                
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Error al crear el reto: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Obtiene todos los retos
     */
    #[Route('/api/listretos', name: 'api_reto_list', methods: ['GET'])]
    public function getRetos(RetoRepository $retoRepository, SerializerInterface $serializer): JsonResponse
    {
        $retos = $retoRepository->findAll();
        
        // Convertir los objetos a un formato serializable
        $data = [];
        foreach ($retos as $reto) {
            $data[] = [
                'id' => $reto->getId(),
                'nombre' => $reto->getTitulo(),
                'descripcion' => $reto->getDescripcion(),
                // Añadir más campos según lo necesite front
            ];
        }
        
        return new JsonResponse($data);
    }

    /**
     * Añade un reto a una aventura
     */
    #[Route('/api/aventura/{aventuraId}', name: 'api_add_reto_aventura', methods: ['POST'])]
    public function addRetoAventura(
        Request $request, 
        EntityManagerInterface $entityManager,
        int $aventuraId,
        RetoRepository $retoRepository
    ): JsonResponse
    {
        // Obtener el ID del reto desde el cuerpo de la solicitud
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['retoId'])) {
            return new JsonResponse(['error' => 'Se requiere el ID del reto'], Response::HTTP_BAD_REQUEST);
        }
        
        $retoId = $data['retoId'];
        
        // Buscar el reto
        $reto = $retoRepository->find($retoId);
        if (!$reto) {
            return new JsonResponse(['error' => 'Reto no encontrado'], Response::HTTP_NOT_FOUND);
        }
        
        // Buscar la aventura (necesitarás un repositorio de aventuras)
        // Suponiendo que tienes un AventuraRepository
        try {
            $aventuraRepository = $entityManager->getRepository('App\Entity\Aventura');
            $aventura = $aventuraRepository->find($aventuraId);
            
            if (!$aventura) {
                return new JsonResponse(['error' => 'Aventura no encontrada'], Response::HTTP_NOT_FOUND);
            }
            
            // Asociar el reto con la aventura
            // Esto dependerá de cómo esté modelada tu relación entre Reto y Aventura
            $aventura->addReto($reto);
            
            $entityManager->flush();
            
            return new JsonResponse([
                'message' => 'Reto añadido a la aventura correctamente'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Error al añadir el reto a la aventura: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/retos/cercanos', name: 'api_retos_cercanos', methods: ['GET'])]
    public function getRetosCercanos(Request $request, RetoRepository $retoRepository): JsonResponse
    {
        $latitud = $request->query->get('lat');
        $longitud = $request->query->get('lng');
        $radio = $request->query->get('radio', 1000); // Radio en metros, por defecto 1km
        
        if (!$latitud || !$longitud) {
            return new JsonResponse(['error' => 'Se requieren parámetros de latitud y longitud'], Response::HTTP_BAD_REQUEST);
        }
        
        // Convertir a números
        $latitud = (float) $latitud;
        $longitud = (float) $longitud;
        $radio = (float) $radio;
        
        // Obtener todos los retos
        $todosLosRetos = $retoRepository->findAll();
        
        // Filtrar retos por distancia
        $retosCercanos = [];
        foreach ($todosLosRetos as $reto) {
            $distancia = $this->calcularDistancia(
                $latitud, 
                $longitud, 
                $reto->getLatitud(), 
                $reto->getLongitud()
            );
            
            // Si está dentro del radio especificado, añadirlo al resultado
            if ($distancia <= $radio) {
                $retosCercanos[] = [
                    'id' => $reto->getId(),
                    'nombre' => $reto->getTitulo(),
                    'descripcion' => $reto->getDescripcion(),
                    'tipo' => $reto->getTipoReto(),
                    'latitud' => $reto->getLatitud(),
                    'longitud' => $reto->getLongitud(),
                    'distancia' => round($distancia, 2), // Distancia en metros redondeada
                    'margen_error' => $reto->getMargenErrorMetros(),
                    'puntos_maximos' => $reto->getPuntosFallo0()
                ];
            }
        }
        
        // Ordenar por distancia (más cercanos primero)
        usort($retosCercanos, function($a, $b) {
            return $a['distancia'] <=> $b['distancia'];
        });
        
        return new JsonResponse([
            'total' => count($retosCercanos),
            'radio_busqueda' => $radio,
            'retos' => $retosCercanos
        ]);
    }
    
    /**
     * Calcula la distancia entre dos puntos geográficos usando la fórmula de Haversine
     */
    private function calcularDistancia($lat1, $lon1, $lat2, $lon2): float
    {
        // Radio de la Tierra en metros
        $radioTierra = 6371000;
        
        // Convertir coordenadas de grados a radianes
        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);
        
        // Diferencias de coordenadas
        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;
        
        // Fórmula de Haversine
        $a = sin($deltaLat/2) * sin($deltaLat/2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLon/2) * sin($deltaLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        // Distancia en metros
        $distancia = $radioTierra * $c;
        
        return $distancia;
    }
}
