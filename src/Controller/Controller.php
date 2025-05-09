<?php
namespace App\Controller;

use App\Controller\AppController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class Controller extends AbstractController {

    #[Route('/app', name: 'app', methods: ['GET'])]
        public function loginView(): Response 
        {
            return $this->render('index.html.twig');
        }

    #[Route('/app/formu', name: 'formu', methods: ['GET'])]
        public function formuEntradaCoche(): Response 
        {
            return $this->render('base.html.twig');
        }
}