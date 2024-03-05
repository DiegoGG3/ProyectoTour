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

   
    #[Route('/rutas_por_localidad/{nombre}', name: 'rutas_por_localidad')]

    public function mostrarRutasPorLocalidadNombre(string $nombre, LocalidadRepository $localidadRepository): Response
    {
        $localidad = $localidadRepository->findOneBy(['nombre' => $nombre]);

        if (!$localidad) {
            throw $this->createNotFoundException('La localidad no fue encontrada.');
        }

        $rutas = $this->rutasPorLocalidadService->obtenerRutasPorLocalidad($localidad);
        $toursPorRuta = [];

        foreach ($rutas as $ruta) {
            $toursPorRuta[$ruta->getId()] = $ruta->getTours();
        }

        return $this->render('rutas_por_localidad.html.twig', [
            'localidad' => $localidad,
            'rutas' => $rutas,
            'toursPorRuta' => $toursPorRuta,
        ]);
    }
}
