<?php

namespace App\Controller;

use App\Entity\Visita;
use App\Entity\Coche;
use App\Entity\Plaza;
use App\Entity\Tipo;
use App\Entity\Estado;

use App\Form\VisitaTypeForm;
use App\Form\CocheTypeForm;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModifyParkingController extends AbstractController
{
    #[Route('/ModifyParking', name: 'app_modify_parking', methods: ['GET', 'POST'])]
    public function modifyParking(Request $request, EntityManagerInterface $entityManager): Response
    {
        // --- Formulario de Visita ---
        $visita = new Visita();
        $visitaForm = $this->createForm(VisitaTypeForm::class, $visita);
        $visitaForm->handleRequest($request);

        if ($visitaForm->isSubmitted() && $visitaForm->isValid()) {
            $data = $visitaForm->getData();
            $plaza = $data->getPlaza();
            $coche = $data->getCoche();
            $estado = $data->getEstado();
            $entrada = $data->getEntrada();
            $salida = $data->getSalida();

            if ($plaza) {
                // Buscar si ya hay una visita con esa plaza
                $visitaExistente = $entityManager->getRepository(Visita::class)
                    ->findOneBy(['plaza' => $plaza]);

                if ($visitaExistente) {
                    // Actualizar la visita existente
                    $visitaExistente->setCoche($coche);
                    $visitaExistente->setEstado($estado);
                    $visitaExistente->setEntrada($entrada);
                    $visitaExistente->setSalida($salida);

                    $entityManager->flush();
                    $this->addFlash('success', 'La visita existente fue modificada.');
                } else {
                    // Crear una nueva visita
                    $entityManager->persist($visita);
                    $entityManager->flush();
                    $this->addFlash('success', 'Se creó una nueva visita.');
                }

                return $this->redirectToRoute('app_modify_parking');
            }
        }


        // --- Formulario de Coche ---
        $coche = null;
        $cocheForm = $this->createForm(CocheTypeForm::class);
        $cocheForm->handleRequest($request);

        if ($cocheForm->isSubmitted()) {
            $matriculaSeleccionada = $cocheForm->get('matricula')->getData();

            if ($matriculaSeleccionada) {
                $coche = $entityManager->getRepository(Coche::class)
                    ->find($matriculaSeleccionada->getMatricula());

                if ($coche && $cocheForm->isValid()) {
                    $coche->setMarca($cocheForm->get('marca')->getData());
                    $coche->setModelo($cocheForm->get('modelo')->getData());
                    $coche->setColor($cocheForm->get('color')->getData());

                    $entityManager->flush();
                    $this->addFlash('success', 'Coche actualizado correctamente.');
                    return $this->redirectToRoute('app_modify_parking');
                }
            } else {
                $this->addFlash('error', 'Debes seleccionar una matrícula válida.');
            }
        }

        // Renderiza la vista con ambos formularios
        return $this->render('modify_parking/index.html.twig', [
            'formulario_visita' => $visitaForm->createView(),
            'formulario_coche' => $cocheForm->createView(),
            'coche' => $coche
        ]);
    }
}
