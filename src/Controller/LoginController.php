<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; // Importación recomendada para Symfony 7+
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    /**
     * Este método manejará tanto la visualización del formulario de login
     * en la raíz de la aplicación (/) como el procesamiento del formulario de login.
     *
     * #[Route('/', name: 'app_login_homepage')] es la ruta para mostrar el formulario
     * #[Route('/login', name: 'app_login')] es la ruta donde el formulario enviará los datos
     *
     * Symfony Security se encargará de interceptar el POST a /login.
     */
    #[Route('/', name: 'app_login_homepage')] // Ahora la raíz (/) apunta directamente al login
    #[Route('/login', name: 'app_login')]    // Mantenemos /login para el submit del formulario (firewall)
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // En esta versión simplificada, no redirigimos si el usuario ya está autenticado.
        // Siempre mostraremos el formulario de login en / o /login.
        // Esto es para la máxima simplicidad de funcionamiento como lo has pedido.

        // Obtener el error de login si lo hay (por ejemplo, credenciales inválidas)
        $error = $authenticationUtils->getLastAuthenticationError();

        // Último nombre de usuario introducido por el usuario (útil para rellenar el campo si hay error)
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): Response
    {
        // Este controlador puede estar vacío, ya que la desconexión
        // será interceptada por el firewall de Symfony.
        // Nunca se debería llegar aquí directamente.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}