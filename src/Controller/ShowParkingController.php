<?php

namespace App\Controller;

use App\Repository\PlazaRepository;
use App\Repository\TipoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowParkingController extends AbstractController
{
    #[Route('/ShowParking', name: 'app_show_parking')]
    public function index(PlazaRepository $plazaRepository , TipoRepository $TipoRepository): Response
    {
        $parkings = $plazaRepository->findAll();
        $type = $TipoRepository->findAll();

        return $this->render('show_parking/index.html.twig', [
            'parkings' => $parkings,
            'types' => $type,
        ]);
    }
}
