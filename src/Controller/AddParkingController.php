<?php

namespace App\Controller;

use App\Entity\Coche;
use App\Entity\Plaza;
use App\Entity\Tipo;
use App\Entity\Estado;

use App\Form\AddCocheTypeForm;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AddParkingController extends AbstractController
{
    #[Route('/AddParking', name: 'app_add_parking')]
    public function modifyVista(Request $request, EntityManagerInterface $entityManager): Response
    {
        // --------------------------
        // FORMULARIO: COCHE (nuevo)
        // --------------------------
        $coche = new Coche();
        $formCoche = $this->createForm(AddCocheTypeForm::class, $coche);
        $formCoche->handleRequest($request);

        if ($formCoche->isSubmitted() && $formCoche->isValid()) {
            $entityManager->persist($coche);
            $entityManager->flush();

            $this->addFlash('success', 'Coche añadido correctamente.');
            return $this->redirectToRoute('app_add_parking');
        }

        return $this->render('add_parking/index.html.twig', [
            'formulario_coche'   => $formCoche->createView()
        ]);
    }
}
