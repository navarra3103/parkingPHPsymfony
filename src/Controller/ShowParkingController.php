<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShowParkingController extends AbstractController
{
    #[Route('/show/parking', name: 'app_show_parking')]
    public function index(): Response
    {
        return $this->render('show_parking/index.html.twig', [
            'controller_name' => 'ShowParkingController',
        ]);
    }
}
