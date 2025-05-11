<?php

namespace App\Controller;

use App\Entity\Visita;
use App\Entity\Coche;
use App\Entity\Plaza;
use App\Form\VisitaTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModifyParkingController extends AbstractController
{
    #[Route('/ModifyParking', name: 'app_modify_parking', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $visita = new Visita();

        // Si el formulario se está enviando
        if ($request->isMethod('POST')) {
            $data = $request->request->all()['visita_type_form'] ?? [];

            $cocheId = $data['coche'] ?? null;
            $plazaId = $data['plaza'] ?? null;

            if ($cocheId && $plazaId) {
                $coche = $entityManager->getRepository(Coche::class)->find($cocheId);
                $plaza = $entityManager->getRepository(Plaza::class)->find($plazaId);

                if ($coche && $plaza) {
                    // Buscar una visita existente con la misma combinación coche+plaza
                    $visitaExistente = $entityManager->getRepository(Visita::class)
                        ->findOneBy(['coche' => $coche, 'plaza' => $plaza]);

                    if ($visitaExistente) {
                        $visita = $visitaExistente;
                    }

                    $visita->setCoche($coche);
                    $visita->setPlaza($plaza);
                }
            }
        }

        $form = $this->createForm(VisitaTypeForm::class, $visita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($visita);
            $entityManager->flush();

            $this->addFlash('success', 'La visita fue guardada correctamente.');
            return $this->redirectToRoute('app_modify_parking');
        }

        return $this->render('modify_parking/index.html.twig', [
            'formulario' => $form->createView(),
        ]);
    }
}
