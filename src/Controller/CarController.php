<?php

namespace App\Controller;

use App\Entity\Coche;
use App\Form\CocheType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class CarController extends AbstractController
{
    #[Route('/app/addCocheManual', name: 'app_coche_nuevo_manual', methods: ['GET', 'POST'])]
    public function nuevoManual(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $marca = $request->request->get('marca');

            if ($marca && $modelo && $color) {
                $coche = new Coche();
                $coche->setMarca($marca);

                $entityManager->persist($coche);
                $entityManager->flush();

                $this->addFlash('success', '¡Coche guardado con éxito!');

                return $this->redirectToRoute('app_coche_index'); // Asegúrate de que esta ruta exista
            } else {
                $this->addFlash('error', 'Por favor, completa todos los campos.');
            }
        }

        return $this->render('coche/nuevo_manual.html.twig'); // Asegúrate de crear esta plantilla
    }
}