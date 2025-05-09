<?php

namespace App\Controller;

use App\Entity\Coche;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CarController extends AbstractController
{
    #[Route('/app/addCoche', name: 'app_coche_nuevo')]
    public function nuevo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coche = new Coche();
        $form = $this->createForm(CocheType::class, $coche);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Obtener los datos del formulario individualmente
            $matricula = $form->get('Matricula')->getData();

            // Asignar los datos a la entidad Coche
            $coche->setMatricula($matricula);

            // Persistir y guardar la entidad
            $entityManager->persist($coche);
            $entityManager->flush();

            $this->addFlash('success', '¡Coche guardado con éxito!');

            return $this->redirectToRoute('app_coche_index'); // Reemplaza con la ruta de tu listado de coches
        }

        return $this->render('index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
