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
        $coche = new Coche();  // Crea una nueva entidad de coche
        $form = $this->createForm(CocheType::class, $coche);  // Crea el formulario con la entidad Coche
        $form->handleRequest($request);  // Maneja la solicitud del formulario

        if ($form->isSubmitted() && $form->isValid()) {
            // Si el formulario se ha enviado y es válido
            $entityManager->persist($coche);  // Persistimos el objeto Coche
            $entityManager->flush();  // Guardamos los cambios en la base de datos

            $this->addFlash('success', '¡Coche guardado con éxito!');
            return $this->redirectToRoute('app');  // Redirige a la ruta de inicio después de guardar
        }

        return $this->render('base.html.twig', [
            'form' => $form->createView(),  // Pasa el formulario a la vista
        ]);
    }

    #[Route('/app/buscarCoches', name: 'buscaCoches', methods: ['GET', 'POST'])]
    public function buscar(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coche = new Coche();  // Crea una nueva entidad de coche para la búsqueda
        $form = $this->createForm(CocheType::class, $coche);  // Crea el formulario con la entidad Coche

        $form->handleRequest($request);  // Maneja la solicitud del formulario

        if ($form->isSubmitted() && $form->isValid()) {
            $matricula = $form->get('Matricula')->getData();  // Obtenemos la matrícula del formulario

            // Realizamos la búsqueda por matrícula
            $coche = $entityManager->getRepository(Coche::class)->findOneBy(['Matricula' => $matricula]);

            if (!$coche) {
                $coche = null;  // Si no se encuentra coche, lo dejamos como null
                $this->addFlash('error', 'No se encontró ningún coche con esa matrícula.');
            }
        }

        return $this->render('buscarCoche.html.twig', [
            'form' => $form->createView(),  // Pasa el formulario a la vista
            'coche' => $coche,  // Pasa el coche encontrado o null
        ]);
    }
}
