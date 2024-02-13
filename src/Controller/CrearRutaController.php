<?php

namespace App\Controller;

use App\Entity\Localidad;
use App\Entity\Visita;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CrearRutaController extends AbstractController
{

    #[Route('/crear/ruta', name: 'app_crear_ruta')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Obtener todas las visitas de la base de datos
        $visitas = $entityManager->getRepository(Visita::class)->findAll();
        $localidades = $entityManager->getRepository(Localidad::class)->findAll();

        // Pasar las visitas a la plantilla Twig
        return $this->render('crear_ruta/index.html.twig', [
            'controller_name' => 'CrearRutaController',
            'visitas' => $visitas,
            'localidades' => $localidades,

        ]);
    }
}
