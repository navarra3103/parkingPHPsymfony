<?php

namespace App\Controller;

use App\Repository\PlazaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowParkingController extends AbstractController
{
    #[Route('/ShowParking', name: 'app_show_parking')]
    public function index(PlazaRepository $plazaRepository): Response
    {
        $parkings = $plazaRepository->findAll();

        return $this->render('show_parking/index.html.twig', [
            'parkings' => $parkings,
        ]);
    }
}
