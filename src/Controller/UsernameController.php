<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
final class UsernameController extends AbstractController
{
    #[Route('/username', name: 'app_username')]
    public function index(UserRepository $userRepository): Response
    {   
        $user = $this->getUser();

        
        if (!$user || !in_array('ROLE_ADMIN', $user->getRoles())) {
            $this->addFlash('error', '❌ No tienes permisos para acceder a esta sección.');
            return $this->redirectToRoute('app_show_parking');
        }

        $users = $userRepository->findAll();

        return $this->render('username/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/username/delete/{id}', name: 'delete_user', methods: ['POST'])]
public function delete(int $id, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
{
    $user = $userRepository->find($id);
    if (!$user) {
        return new JsonResponse(['status' => 'not_found'], 404);
    }

    // Si tiene el rol de admin y es el único admin => no permitir borrar
    $adminUsers = array_filter($userRepository->findAll(), function ($u) {
        return in_array('ROLE_ADMIN', $u->getRoles());
    });

    if (in_array('ROLE_ADMIN', $user->getRoles()) && count($adminUsers) === 1) {
        return new JsonResponse(['status' => 'cannot_delete_last_admin'], 403);
    }

    $em->remove($user);
    $em->flush();

    return new JsonResponse(['status' => 'deleted']);
}

    #[Route('/username/change-password/{id}', name: 'username_change_password', methods: ['POST'])]
    public function changePassword(
        int $id,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $user = $userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['status' => 'user_not_found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $newPassword = $data['password'] ?? null;

        if (!$newPassword) {
            return new JsonResponse(['status' => 'missing_password'], 400);
        }

        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
        $em->flush();

        return new JsonResponse(['status' => 'password_updated']);
    }
    #[Route('/username/make-admin/{id}', name: 'username_make_admin', methods: ['POST'])]
    public function makeAdmin(int $id, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
    {
        $user = $userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['status' => 'user_not_found'], 404);
        }

        $roles = $user->getRoles();
        if (!in_array('ROLE_ADMIN', $roles)) {
            $roles[] = 'ROLE_ADMIN';
            $user->setRoles(array_unique($roles));
            $em->flush();
        }

        return new JsonResponse(['status' => 'role_updated']);
    }
    #[Route('/username/remove-admin/{id}', name: 'remove_admin', methods: ['POST'])]
#[Route('/username/remove-admin/{id}', name: 'remove_admin', methods: ['POST'])]
public function removeAdmin(int $id, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
{
    $user = $userRepository->find($id);
    if (!$user) {
        return new JsonResponse(['status' => 'not_found'], 404);
    }

    // Buscar cuántos admins existen
    $allUsers = $userRepository->findAll();
    $adminUsers = array_filter($allUsers, function ($u) {
        return in_array('ROLE_ADMIN', $u->getRoles());
    });

    // Si es el único admin, no dejar quitar el rol
    if (count($adminUsers) === 1 && $adminUsers[0]->getId() === $user->getId()) {
        return new JsonResponse(['status' => 'last_admin_forbidden'], 403);
    }

    // Quitar el rol
    $roles = array_filter($user->getRoles(), fn($role) => $role !== 'ROLE_ADMIN');
    $user->setRoles(array_values($roles)); // reindexar
    $em->flush();

    return new JsonResponse(['status' => 'admin_removed']);
}
}
