<?php
// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Entity\User; // Importa tu entidad User
use App\Form\RegistrationFormType; // Aún no existe, la crearemos en el Paso 3
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Para Symfony 5.3+ y 6+
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // 1. Crear una nueva instancia de la entidad User
        $user = new User();

        // 2. Crear el formulario de registro.
        //    RegistrationFormType aún no existe, pero lo haremos en el Paso 3.
        $form = $this->createForm(RegistrationFormType::class, $user);

        // 3. Manejar la solicitud (si el formulario fue enviado)
        $form->handleRequest($request);

        // 4. Verificar si el formulario ha sido enviado y es válido
        if ($form->isSubmitted() && $form->isValid()) {
            // Codificar la contraseña
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData() // 'plainPassword' será un campo en el formulario
                )
            );

            // Asignar roles (por defecto, ROLE_USER)
            $user->setRoles(['ROLE_USER']);

            // Persistir el usuario en la base de datos
            $entityManager->persist($user);
            $entityManager->flush();

            // Añadir un mensaje flash de éxito
            $this->addFlash(
                'success',
                '¡Tu cuenta ha sido creada exitosamente! Ya puedes iniciar sesión.'
            );

            // Redirigir al usuario a la página de login
            return $this->redirectToRoute('app_login'); // 'app_login' es la ruta de tu formulario de login
        }

        // 5. Renderizar el formulario (si no se ha enviado o no es válido)
        return $this->render('registration/register.html.twig', [ // Usaremos 'register.html.twig'
            'registrationForm' => $form->createView(),
        ]);
    }
}