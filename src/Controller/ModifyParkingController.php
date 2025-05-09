<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ModifyParkingController extends AbstractController
{
    #[Route('/modify/parking', name: 'app_modify_parking')]
    public function index(): Response
    {
        return $this->render('modify_parking/index.html.twig', [
            'controller_name' => 'ModifyParkingController',
        ]);
    }
}
