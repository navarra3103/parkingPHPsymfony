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
        $coche = new Coche();
        $formCoche = $this->createForm(AddCocheTypeForm::class, $coche);
        $formCoche->handleRequest($request);

        if ($formCoche->isSubmitted() && $formCoche->isValid()) {
            $coches = $entityManager->getRepository(Coche::class)->findAll();

            foreach ($coches as $c) {
                if ($c->getMatricula() === $coche->getMatricula()) {
                    if ($c->getEstado() !== $coche->getEstado()) {
                        $c->setEstado($coche->getEstado());
                    }

                    if ($c->getTipo() !== $coche->getTipo()) {
                        $c->setTipo($coche->getTipo());
                    }

                    $this->addFlash('existe', 'Se han modificado los datos del coche.');
                    return $this->redirectToRoute('app_add_parking');
                }
            }

            $entityManager->persist($coche);
            $entityManager->flush();

            $this->addFlash('success', 'Coche añadido correctamente.');
            return $this->redirectToRoute('app_add_parking');
        }

        return $this->render('add_parking/index.html.twig', [
            'formulario_coche' => $formCoche->createView()
        ]);
    }

    #[Route('/AddParking/Show_cars', name: 'app_show_cars')]
    public function showCars(EntityManagerInterface $entityManager): Response
    {
        $coches = $entityManager->getRepository(Coche::class)->findAll();

        return $this->render('add_parking/show_cars.html.twig', [
            'coches' => $coches,
        ]);
    }

    #[Route('/AddParking/EditCar/{matricula}', name: 'app_edit_car')]
    public function editCar(Request $request, EntityManagerInterface $entityManager, string $matricula): Response
    {
        $coche = $entityManager->getRepository(Coche::class)->find($matricula);

        if (!$coche) {
            throw $this->createNotFoundException('Coche no encontrado');
        }

        $form = $this->createForm(AddCocheTypeForm::class, $coche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Coche actualizado correctamente.');
            return $this->redirectToRoute('app_show_cars');
        }

        return $this->render('add_parking/edit_car.html.twig', [
            'formulario_editar_coche' => $form->createView(),
            'matricula' => $matricula,
        ]);
    }
}
