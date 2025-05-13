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

final class ShowParkingController extends AbstractController
{
    #[Route('/ShowParking', name: 'app_show_parking')]
    public function showParking(EntityManagerInterface $em): Response
{
    $plazas = $em->getRepository(Plaza::class)->findAll();

    // Traer visitas activas, opcionalmente por estado
    $visitas = $em->getRepository(Visita::class)->findBy([
        'estado' => $em->getRepository(Estado::class)->findOneBy(['Nombre' => 'Recepcionado']) // o tu lógica
    ]);

    // Crear un mapa [idPlaza => matricula]
    $mapaVisitas = [];
    foreach ($visitas as $visita) {
        $plaza = $visita->getPlaza();
        $coche = $visita->getCoche();
        if ($plaza && $coche) {
            $mapaVisitas[$plaza->getIdPlaza()] = $coche->getMatricula();
        }
    }

    return $this->render('show_parking/index.html.twig', [
        'parkings' => $plazas,
        'matriculas' => $mapaVisitas,
        'types' => $em->getRepository(Tipo::class)->findAll(),
    ]);
}

}
