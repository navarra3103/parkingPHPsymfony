<?php

namespace App\Controller;

use App\Entity\Coche;
use App\Form\CocheType; // ¡Importa la clase de formulario!
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class CarController extends AbstractController
{
    #[Route('/app/addCoche', name: 'app_coche_nuevo', methods:"POST")]
    public function nuevo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coche = new Coche();
        $form = $this->createForm(CocheType::class, $coche); // Usa CocheType::class

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Los datos del formulario ya están mapeados automáticamente a la entidad $coche
            // No necesitas obtener los datos individualmente a menos que necesites lógica adicional.

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