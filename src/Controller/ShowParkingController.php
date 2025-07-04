<?php

namespace App\Controller;

use App\Entity\Plaza;
use App\Entity\Estado;
use App\Entity\Visita;
use App\Entity\Tipo;
use App\Entity\Coche;
use App\Entity\Historico;

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

            $ocupada = isset($mapaVisitas[$id]);

            $result[] = [
                'id' => $id,
                'color' => $tipoColor,
                'tipo' => $plaza->getTipo()?->getIdTipo(),
                'matricula' => $info['matricula'],
                'estado' => $info['estado'],
                'entrada' => $info['entrada'],
                'ocupada' => $ocupada,
            ];
        }

        return $this->json($result);
    }



    // Crear Tipo
        #[Route('/ShowParking/add-tipo', name: 'add_tipo', methods: ['POST'])]
            public function addTipo(Request $request, EntityManagerInterface $em): Response
            {
                $nombre = $request->request->get('crear-nombre');
                $color = $request->request->get('crear-color');

                // Validar
                if (empty($nombre)) {
                    $this->addFlash('error', 'Debes completar todos los campos.');
                    return $this->redirectToRoute('app_show_parking');
                }

                $tipo = new Tipo();
                $tipo->setNombre($nombre);
                $tipo->setColor($color);

                $em->persist($tipo);
                $em->flush();
                
                return $this->redirectToRoute('app_show_parking');
            }

    // Modificar Tipo
        #[Route('/ShowParking/update-tipo', name: 'update_tipo', methods: ['POST'])]
            public function updateTipo(Request $request, EntityManagerInterface $em): Response
            {
                $id = $request->request->get('idTipo');
                $nombre = $request->request->get('nombre');
                $color = $request->request->get('color');

                if (empty($id)) {
                    $this->addFlash('error', 'ID del tipo no proporcionado.');
                    return $this->redirectToRoute('app_show_parking');
                }
                if (empty($nombre)) {
                    $this->addFlash('error', 'El nombre no puede estar vacío.');
                    return $this->redirectToRoute('app_show_parking');
                }

                $tipo = $em->getRepository(Tipo::class)->find($id);
                if (!$tipo) {
                    $this->addFlash('error', 'Tipo no encontrado.');
                    return $this->redirectToRoute('app_show_parking');
                }


                if ($nombre !== null) {
                    $tipo->setNombre($nombre);
                }

                if ($color !== null) {
                    $tipo->setColor($color);
                }

                $em->flush();

                return $this->redirectToRoute('app_show_parking');
            }

    // Eliminar Tipo
        #[Route('/ShowParking/delete-tipo', name: 'delete_tipo', methods: ['POST'])]
            public function eliminarTipo(Request $request, EntityManagerInterface $em): Response
            {
                $id = $request->request->get('eliminar-id');
            
                if (null === $id || !is_numeric($id)) {
                    $this->addFlash('error', 'ID del tipo no válido o no proporcionado.');
                    return $this->redirectToRoute('app_show_parking'); // Redirige a la página principal
                }

                $tipo = $em->getRepository(Tipo::class)->findOneBy(['idTipo' => $id]);

                if (!$tipo) {
                    $this->addFlash('error', 'Tipo no encontrado.');
                    return $this->redirectToRoute('app_show_parking'); // Redirige a la página principal
                }

                $em->remove($tipo);
                $em->flush();
                
                $this->addFlash('success', 'Tipo eliminado correctamente.');
                return $this->redirectToRoute('app_show_parking');
            }

// Modificar visita
    #[Route('/ShowParking/modifyVisit', name: 'modify_visit', methods: ['POST'])]
        public function modifyVisit(Request $request, EntityManagerInterface $em): Response
        {
            $plazaId = $request->request->get('plaza');
            $matricula = $request->request->get('matricula');
            $estadoId = $request->request->get('estado');
            $tipoId = $request->request->get('tipo');
                
            $error = null;
                
            if (empty($tipoId)) {
                $error = 'Debes seleccionar un tipo.';
            } else {
                $tipo = $em->getRepository(Tipo::class)->find($tipoId);
                $plaza = $em->getRepository(Plaza::class)->find($plazaId);
            
                if (!$tipo || !$plaza) {
                    $error = 'Tipo o Plaza no válido.';
                } else {
                    // Modificar solo el tipo si no hay matrícula
                    $plaza->setTipo($tipo);
                    $em->persist($plaza);
                
                    // Si hay matrícula, entonces validar todo lo demás
                    if (!empty($matricula)) {
                        if (empty($estadoId)) {
                            $error = 'Debes seleccionar un estado.';
                        } else {
                            $estado = $em->getRepository(Estado::class)->find($estadoId);
                            $coche = $em->getRepository(Coche::class)->find($matricula);
                        
                            if (!$estado || !$coche) {
                                $error = 'Estado o Coche no válido.';
                            } else {
                                $visitaExistente = $em->getRepository(Visita::class)->findOneBy(['coche' => $coche]);
                                if ($visitaExistente && $visitaExistente->getPlaza()->getIdPlaza() !== $plaza->getIdPlaza()) {
                                    $error = 'Esta matrícula ya está asignada a otra plaza.';
                                } else {
                                    $visita = $em->getRepository(Visita::class)->findOneBy(['plaza' => $plaza]);
                                    if (!$visita) {
                                        $visita = new Visita();
                                        $visita->setPlaza($plaza);
                                        $visita->setEntrada(new \DateTime());
                                    }
                                
                                    $visita->setEstado($estado);
                                    $visita->setCoche($coche);
                                    $em->persist($visita);
                                }
                            }
                        }
                    }
                }
            }
        
            if ($error) {
                $this->addFlash('error', $error);
            } else {
                $em->flush();
            }
        
            return $this->redirectToRoute('app_show_parking');
        }

//Eliminar visita 
    #[Route('/ShowParking/deleteVisit', name: 'delete_visit', methods: ['POST'])]
        public function deleteVisit(Request $request, EntityManagerInterface $em): Response
        {
            $plazaId = $request->request->get('plaza');
        
            // Buscar la visita por la plaza
            $visita = $em->getRepository(Visita::class)->findOneBy(['plaza' => $plazaId]);
        
            if ($visita) {
                // Crear registro en Historico (sin estado)
                $historico = new Historico();
                $historico->setCoche($visita->getCoche());
                $historico->setPlaza($visita->getPlaza());
                $historico->setEntrada($visita->getEntrada());
                $historico->setSalida(new \DateTime()); // salida = ahora
            
                $em->persist($historico);
                $em->remove($visita);
                $em->flush();
            
                return new Response('Visita eliminada y registrada en el historial.', 200);
            }
        
            return new Response('No se encontró visita', 404);
        }


}
