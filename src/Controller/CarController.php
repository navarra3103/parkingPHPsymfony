<?php

namespace App\Controller;

use App\Entity\Coche;
use App\Form\CocheType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

final class CarController extends AbstractController
{
    #[Route('/app', name: 'app', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/app/formu', name: 'formu', methods: ['GET', 'POST'])]
    public function nuevo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coche = new Coche();
        $form = $this->createForm(CocheType::class, $coche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($coche);
            $entityManager->flush();

            $this->addFlash('success', '¡Coche guardado con éxito!');
            return $this->redirectToRoute('app');
        }

        return $this->render('base.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/app/buscarCoches', name: 'buscaCoches', methods: ['GET', 'POST'])]
    public function buscar(Request $request): Response
    {
        $coche = new Coche();
        $form = $this->createForm(CocheType::class, $coche);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matricula = $form->get('Matricula')->getData();

            // Aquí deberías buscar el coche por matrícula
            // Ejemplo (requiere el repositorio de Coche):
            // $resultados = $entityManager->getRepository(Coche::class)->findBy(['Matricula' => $matricula]);

            $this->addFlash('info', "Buscando coche con matrícula: $matricula");
        }

        return $this->render('buscarCoche.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
