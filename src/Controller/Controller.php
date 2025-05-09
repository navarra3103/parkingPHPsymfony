<?php
namespace App\Controller;

class Controller extends AbstractController {

    #[Route('/app', name: 'app', methods: ['GET'])]
        public function loginView(): Response 
        {
            return $this->render('index.html.twig');
        }

    #[Route('/app/formu', name: 'formu', methods: ['GET'])]
        public function formuEntradaCoche(): Response 
        {
            return $this->render('formularioCoche.html.twig');
        }
}