<?php

namespace App\Controller;

use App\Entity\Plaza;
use App\Entity\Estado;
use App\Entity\Visita;
use App\Entity\Tipo;
use App\Entity\Coche;

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
                ];
            }
        }

        // Obtener tipos , estados y matriculas
        $tipos = $em->getRepository(Tipo::class)->findAll();
        $estados = $em->getRepository(Estado::class)->findAll();
        $matriculas = $em->getRepository(Coche::class)->findAll();


        return $this->render('show_parking/index.html.twig', [
            'parkings' => $plazas,
            'mapaVisitas' => $mapaVisitas,
            'types' => $tipos,
            'estados' => $estados,
            'matriculas' => $matriculas,
        ]);
    }

    #[Route('/api/plazas', name: 'api_plazas')]
    public function getPlazas(EntityManagerInterface $em): JsonResponse
    {
        $plazas = $em->getRepository(Plaza::class)->findAll();
        $visitas = $em->getRepository(Visita::class)->findAll();

        $mapaVisitas = [];
        foreach ($visitas as $visita) {
            $plazaId = $visita->getPlaza()->getIdPlaza();
            $mapaVisitas[$plazaId] = [
                'matricula' => $visita->getCoche()->getMatricula(),
                'estado' => $visita->getEstado()->getNombre(),
                'entrada' => $visita->getEntrada()?->format('Y-m-d H:i'),
            ];
        }

        $result = [];
        foreach ($plazas as $plaza) {
            $tipoColor = $plaza->getTipo()?->getColor() ?? '#CCCCCC';
            $id = $plaza->getIdPlaza();

            $info = $mapaVisitas[$id] ?? [
                'matricula' => null,
                'estado' => null,
                'entrada' => null,
            ];

            $result[] = [
                'id' => $id,
                'color' => $tipoColor,
                'matricula' => $info['matricula'],
                'estado' => $info['estado'],
                'entrada' => $info['entrada'],
            ];
        }

        return $this->json($result);
    }



    #[Route('/ShowParking/add-tipo', name: 'add_tipo', methods: ['POST'])]
    public function addTipo(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $nombre = $request->request->get('nombre');
        $color = $request->request->get('color');

        if (!$nombre || !$color) {
            return $this->json(['error' => 'Faltan campos'], 400);
        }

        $tipo = new Tipo();
        $tipo->setNombre($nombre);
        $tipo->setColor($color);
        $em->persist($tipo);
        $em->flush();

        return $this->json(['success' => true, 'tipo' => [
            'id' => $tipo->getIdTipo(),
            'nombre' => $tipo->getNombre(),
            'color' => $tipo->getColor(),
        ]]);
    }

    #[Route('/ShowParking/update-tipo', name: 'update_tipo', methods: ['POST'])]
    public function updateTipo(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $id = $request->request->get('id');
        $nombre = $request->request->get('nombre');
        $color = $request->request->get('color');
        $tipo = $em->getRepository(Tipo::class)->find($id);
        if (!$tipo) {
            return $this->json(['error' => 'Tipo no encontrado'], 404);
        }
        if ($nombre) $tipo->setNombre($nombre);
        if ($color) $tipo->setColor($color);
        $em->flush();
        return $this->json(['success' => true]);
    }

    #[Route('/ShowParking/modifyVisit', name: 'modify_visit', methods: ['POST'])]
    public function modifyVisit(Request $request, EntityManagerInterface $em): Response
    {
        $plazaId = $request->request->get('plaza');
        $matricula = $request->request->get('matricula');
        $estadoId = $request->request->get('estado');

         // Validar que se haya seleccionado un estado
        if (empty($estadoId)) {
            return $this->redirectToRoute('app_show_parking');
        }

        $plaza = $em->getRepository(Plaza::class)->find($plazaId);
        $estado = $em->getRepository(Estado::class)->find($estadoId);
        $coche = $em->getRepository(Coche::class)->find($matricula);

        if (!$plaza || !$estado || !$coche) {
            $this->addFlash('error', 'Plaza, Estado o Coche no válido');
            return $this->redirectToRoute('app_show_parking');
        }

        // Buscar o crear la visita
        $visita = $em->getRepository(Visita::class)->findOneBy(['plaza' => $plaza]);
        if (!$visita) {
            $visita = new Visita();
            $visita->setPlaza($plaza);
            $visita->setEntrada(new \DateTime()); // se establece la entrada al crear
        }

        // Asignar estado y coche (ya existente)
        $visita->setEstado($estado);
        $visita->setCoche($coche);

        $em->persist($visita);
        $em->flush();

        $this->addFlash('success', 'Visita modificada correctamente.');
        return $this->redirectToRoute('app_show_parking');
    }

    #[Route('/ShowParking/deleteVisit', name: 'delete_visit', methods: ['POST'])]
    public function deleteVisit(Request $request, EntityManagerInterface $em): Response
    {
        $plazaId = $request->request->get('plaza');
        $visita = $em->getRepository(Visita::class)->findOneBy(['plaza' => $plazaId]);

        if ($visita) {
            $em->remove($visita);
            $em->flush();

            return new Response('Visita eliminada', 200);
        }

        return new Response('No se encontró visita', 404);
    }

}
