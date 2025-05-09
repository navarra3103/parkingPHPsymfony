<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AddParkingController extends AbstractController
{
    #[Route('/add/parking', name: 'app_add_parking')]
    public function index(): Response
    {
        return $this->render('add_parking/index.html.twig', [
            'controller_name' => 'AddParkingController',
        ]);
    }
}
