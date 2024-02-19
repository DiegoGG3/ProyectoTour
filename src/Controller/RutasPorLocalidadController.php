<?php

namespace App\Controller;

use App\Repository\LocalidadRepository;
use App\Service\RutasPorLocalidadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RutasPorLocalidadController extends AbstractController
{
    private $rutasPorLocalidadService;

    public function __construct(RutasPorLocalidadService $rutasPorLocalidadService)
    {
        $this->rutasPorLocalidadService = $rutasPorLocalidadService;
    }

    /**
     * @Route("/rutas_por_localidad/{nombre}", name="rutas_por_localidad_nombre")
     */
    #[Route('/rutas_por_localidad/{nombre}', name: 'rutas_por_localidad')]

    public function mostrarRutasPorLocalidadNombre(string $nombre, LocalidadRepository $localidadRepository): Response
    {
        // Buscar la localidad por su nombre
        $localidad = $localidadRepository->findOneBy(['nombre' => $nombre]);

        if (!$localidad) {
            throw $this->createNotFoundException('La localidad no fue encontrada.');
        }

        $rutas = $this->rutasPorLocalidadService->obtenerRutasPorLocalidad($localidad);

        return $this->render('rutas_por_localidad.html.twig', [ // Aquí se especifica el nombre real de la plantilla
            'localidad' => $localidad,
            'rutas' => $rutas,
        ]);
    }
}
