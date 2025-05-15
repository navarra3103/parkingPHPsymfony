<?php

namespace App\Controller;

use App\Entity\Plaza;
use App\Entity\Estado;
use App\Entity\Visita;
use App\Entity\Tipo;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ShowParkingController extends AbstractController
{
    #[Route('/ShowParking', name: 'app_show_parking')]
    public function showParking(Request $request, EntityManagerInterface $em): Response
    {
        // Obtener todas las plazas
        $plazas = $em->getRepository(Plaza::class)->findAll();

        // Obtener el estado seleccionado desde la URL (si hay)
        $estadoId = $request->query->get('estado');

        // Filtrar visitas según estado si se selecciona
        if ($estadoId) {
            $estado = $em->getRepository(Estado::class)->find($estadoId);
            $visitas = $em->getRepository(Visita::class)->findBy(['estado' => $estado]);
        } else {
            $visitas = $em->getRepository(Visita::class)->findAll();
        }

        // Construir mapa [idPlaza => ['matricula' => ..., 'estado' => ...]]
        $mapaVisitas = [];
        foreach ($visitas as $visita) {
            $plaza = $visita->getPlaza();
            $coche = $visita->getCoche();
            $estado = $visita->getEstado();

            if ($plaza && $coche && $estado) {
                $mapaVisitas[$plaza->getIdPlaza()] = [
                    'matricula' => $coche->getMatricula(),
                    'estado' => $estado->getNombre(),
                    'entrada' => $visita->getEntrada(),
                    'salida' => $visita->getSalida(),
                ];
            }
        }

        // Obtener tipos (si los usas en la vista)
        $tipos = $em->getRepository(Tipo::class)->findAll();

        return $this->render('show_parking/index.html.twig', [
            'parkings' => $plazas,
            'mapaVisitas' => $mapaVisitas,
            'types' => $tipos,
        ]);
    }

    #[Route('/api/plazas', name: 'api_plazas')]
    public function apiPlazas(EntityManagerInterface $em): JsonResponse
    {
        $plazas = $em->getRepository(Plaza::class)->findAll();

        $data = [];
        foreach ($plazas as $plaza) {
            $tipo = $plaza->getTipo();
            $color = $tipo ? $tipo->getColor() : 'gray';

            $data[] = [
                'id' => $plaza->getIdPlaza(),
                'color' => $color,
            ];
        }

        return $this->json($data);
    }
}
