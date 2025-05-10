<?php

namespace App\Controller;

use App\Entity\Visita;
use App\Entity\Plaza;
use App\Form\VisitaTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModifyParkingController extends AbstractController
{
    #[Route('/ModifyParking/{plazaId}', name: 'app_modify_parking', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, int $plazaId): Response
    {
        // 1. Obtener la plaza
        $plaza = $entityManager->getRepository(Plaza::class)->find($plazaId);
        if (!$plaza) {
            throw $this->createNotFoundException(message: 'No se encontró la plaza con el ID: '.$plazaId);
        }

        // 2. Obtener la visita actual de la plaza o crear una nueva
        $visita = $entityManager->getRepository(Visita::class)->findOneBy(['Plaza' => $plaza]);
        $esEdicion = true; // Inicialmente asumimos que es edición
        if (!$visita) {
            $visita = new Visita();
            $visita->setPlaza($plaza); // Asignar la plaza a la nueva visita
            $esEdicion = false; // Si no existía, es creación
        }

        // 3. Crear y manejar el formulario
        $form = $this->createForm(VisitaTypeForm::class, $visita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($esEdicion) {
                $entityManager->flush();
                $this->addFlash('success', 'La visita ha sido modificada correctamente.');
            } else {
                $entityManager->persist($visita);
                $entityManager->flush();
                $this->addFlash('success', 'La visita ha sido creada correctamente.');
            }

            // Redirigir a la misma página para mostrar los datos actualizados
            return $this->redirectToRoute('app_modify_parking_plaza', ['plazaId' => $plazaId]);
        }

        // 4. Renderizar la plantilla
        return $this->render('modify_parking/index.html.twig', [ // Asegúrate de que este template existe
            'formulario' => $form->createView(),
            'visita' => $visita,
            'plaza' => $plaza, // Pasar la plaza a la plantilla para mostrar información
            'esEdicion' => $esEdicion,
        ]);
    }
}
