<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $passwordHasher, 
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        
        // Validar que todos los campos requeridos estén presentes
        $requiredFields = [
            'nombre_completo', 'apodo', 'genero', 'fecha_nacimiento', 
            'municipio', 'vive_palmilla', 'email', 'confirmacion_email',
            'telefono', 'confirmacion_telefono', 'password', 'acepta_politica'
        ];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return $this->json([
                    'success' => false,
                    'message' => "El campo '$field' es obligatorio"
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        
        // Validar que los emails coinciden
        if ($data['email'] !== $data['confirmacion_email']) {
            return $this->json([
                'success' => false,
                'message' => 'Los correos electrónicos no coinciden'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // Validar que los teléfonos coinciden
        if ($data['telefono'] !== $data['confirmacion_telefono']) {
            return $this->json([
                'success' => false,
                'message' => 'Los números de teléfono no coinciden'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // Validar que se ha aceptado la política de privacidad
        if (!$data['acepta_politica']) {
            return $this->json([
                'success' => false,
                'message' => 'Debes aceptar la política de privacidad'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // Verificar si el email ya existe
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return $this->json([
                'success' => false,
                'message' => 'Este correo electrónico ya está registrado'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // Crear nuevo usuario
        $user = new User();
        $user->setNombreCompleto($data['nombre_completo']);
        $user->setApodo($data['apodo']);
        $user->setGenero($data['genero']);
        
        // Convertir la fecha de nacimiento - CORREGIDO
        try {
            // Asegurarse de que la fecha esté en formato Y-m-d
            $fechaNacimiento = \DateTime::createFromFormat('Y-m-d', $data['fecha_nacimiento']);
            
            if (!$fechaNacimiento) {
                // Intentar con otro formato común
                $fechaNacimiento = \DateTime::createFromFormat('d/m/Y', $data['fecha_nacimiento']);
            }
            
            if (!$fechaNacimiento) {
                throw new \Exception('Formato de fecha no reconocido');
            }
            
            $user->setFechaNacimiento($fechaNacimiento);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Formato de fecha inválido. Utiliza el formato YYYY-MM-DD'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $user->setMunicipio($data['municipio']);
        $user->setVivePalmilla($data['vive_palmilla']);
        $user->setEmail($data['email']);
        $user->setTelefono($data['telefono']);
        $user->setAceptaPolitica($data['acepta_politica']);
        
        // Hashear la contraseña
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $data['password']
        );
        $user->setPassword($hashedPassword);
        
        // Establecer rol por defecto - CORREGIDO
        // Asegúrate de que el método setRoles acepta un array
        $roles = ['ROLE_USER'];
        $user->setRoles($roles);
        
        // Establecer fechas de creación y actualización
        $now = new \DateTime();
        $user->setCreadoEn($now);
        $user->setActualizadoEn($now);
        
        // Validar la entidad
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            
            return $this->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $errorMessages
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // Guardar el usuario
        $entityManager->persist($user);
        $entityManager->flush();
        
        return $this->json([
            'success' => true,
            'message' => 'Usuario registrado correctamente'
        ], Response::HTTP_CREATED);
    }
}