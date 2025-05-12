<?php

namespace App\Controller;

use App\Entity\Coche;
use App\Entity\Plaza;
use App\Entity\Tipo;
use App\Entity\Estado;

use App\Form\AddCocheTypeForm;
use App\Form\AddPlazaTypeForm;
use App\Form\AddTipoTypeForm;
use App\Form\AddEstadoTypeForm;

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

        // --------------------------
        // FORMULARIO: PLAZA (nueva)
        // --------------------------
        $plaza = new Plaza();
        $formPlaza = $this->createForm(AddPlazaTypeForm::class, $plaza);
        $formPlaza->handleRequest($request);

        if ($formPlaza->isSubmitted() && $formPlaza->isValid()) {
            $entityManager->persist($plaza);
            $entityManager->flush();

            $this->addFlash('success', 'Plaza añadida correctamente.');
            return $this->redirectToRoute('app_add_parking');
        }

        // -------------------------
        // FORMULARIO: TIPO (nuevo)
        // -------------------------
        $tipo = new Tipo();
        $formTipo = $this->createForm(AddTipoTypeForm::class, $tipo);
        $formTipo->handleRequest($request);

        if ($formTipo->isSubmitted() && $formTipo->isValid()) {
            $entityManager->persist($tipo);
            $entityManager->flush();

            $this->addFlash('success', 'Tipo añadido correctamente.');
            return $this->redirectToRoute('app_add_parking');
        }

        // ---------------------------
        // FORMULARIO: ESTADO (nuevo)
        // ---------------------------
        $estado = new Estado();
        $formEstado = $this->createForm(AddEstadoTypeForm::class, $estado);
        $formEstado->handleRequest($request);

        if ($formEstado->isSubmitted() && $formEstado->isValid()) {
            $entityManager->persist($estado);
            $entityManager->flush();

            $this->addFlash('success', 'Estado añadido correctamente.');
            return $this->redirectToRoute('app_add_parking');
        }

        return $this->render('add_parking/index.html.twig', [
            'formulario_coche'   => $formCoche->createView(),
            'formulario_plaza'   => $formPlaza->createView(),
            'formulario_tipo'    => $formTipo->createView(),
            'formulario_estado'  => $formEstado->createView(),
        ]);
    }
}
