<?php

namespace App\Controller;

use App\Entity\Visita;
use App\Entity\Coche;
use App\Entity\Plaza;
use App\Entity\Tipo;
use App\Entity\Estado;

use App\Form\PlazaTypeForm;
use App\Form\VisitaTypeForm;
use App\Form\CocheTypeForm;
use App\Form\TipoTypeForm;
use App\Form\EstadoTypeForm;

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
            $coche = $data->getCoche();
            $plaza = $data->getPlaza();

            if ($coche && $plaza) {
                // Buscar visita existente
                $visitaExistente = $entityManager->getRepository(Visita::class)
                    ->findOneBy(['coche' => $coche, 'plaza' => $plaza]);

                if ($visitaExistente) {
                    $visita = $visitaExistente;
                    $visita->setCoche($coche);
                    $visita->setPlaza($plaza);
                }

                $entityManager->persist($visita);
                $entityManager->flush();

                $this->addFlash('success', 'La visita fue guardada correctamente.');
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

        // --- Formulario de Plaza ---
        $plazaForm = $this->createForm(PlazaTypeForm::class);
        $plazaForm->handleRequest($request);

        if ($plazaForm->isSubmitted()) {
            $selectedPlaza = $plazaForm->get('idPlaza')->getData();
        
            if ($selectedPlaza instanceof Plaza) {
                if ($plazaForm->isValid()) {
                    $tipoSeleccionado = $plazaForm->get('tipo')->getData();
                    $selectedPlaza->setTipo($tipoSeleccionado);
                
                    $entityManager->flush();
                
                    $this->addFlash('success', 'Plaza modificada correctamente.');
                    return $this->redirectToRoute('app_modify_parking');
                }
            } else {
                $this->addFlash('error', 'Debes seleccionar una plaza válida.');
            }
        }
        // --- Formulario de Tipo ---
        $tipoForm = $this->createForm(TipoTypeForm::class);
        $tipoForm->handleRequest($request);

        if ($tipoForm->isSubmitted()) {
            $tipoSeleccionado = $tipoForm->get('idTipo')->getData();
        
            if ($tipoSeleccionado instanceof Tipo) {
                if ($tipoForm->isValid()) {
                    $tipoSeleccionado->setNombre($tipoForm->get('nombre')->getData());
                    $tipoSeleccionado->setColor($tipoForm->get('color')->getData());
                
                    $entityManager->flush();
                
                    $this->addFlash('success', 'Tipo actualizado correctamente.');
                    return $this->redirectToRoute('app_modify_parking');
                }
            } else {
                $this->addFlash('error', 'Debes seleccionar un tipo válido.');
            }
        }
        // --- Formulario de Estado ---
        $estadoForm = $this->createForm(EstadoTypeForm::class);
        $estadoForm->handleRequest($request);

        if ($estadoForm->isSubmitted()) {
            $estadoSeleccionado = $estadoForm->get('idEstado')->getData();
        
            if ($estadoSeleccionado instanceof Estado) {
                if ($estadoForm->isValid()) {
                    $estadoSeleccionado->setNombre($estadoForm->get('nombre')->getData());
                
                    $entityManager->flush();
                
                    $this->addFlash('success', 'Estado actualizado correctamente.');
                    return $this->redirectToRoute('app_modify_parking');
                }
            } else {
                $this->addFlash('error', 'Debes seleccionar un estado válido.');
            }
        }

        // Renderiza la vista con ambos formularios
        return $this->render('modify_parking/index.html.twig', [
            'formulario_visita' => $visitaForm->createView(),
            'formulario_coche' => $cocheForm->createView(),
            'formulario_plaza' => $plazaForm->createView(),
            'formulario_tipo' => $tipoForm->createView(),
            'formulario_estado' => $estadoForm->createView(),
            'coche' => $coche,
        ]);
    }
}
