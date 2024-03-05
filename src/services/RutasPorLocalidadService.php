<?php

namespace App\Service;

use App\Entity\Localidad;
use Doctrine\ORM\EntityManagerInterface;

class RutasPorLocalidadService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function obtenerRutasPorLocalidad(Localidad $localidad): array
{
    $rutas = [];

    $visitas = $localidad->getVisitas();

    foreach ($visitas as $visita) {
        $rutasDeVisita = $visita->getRutas()->toArray();
        foreach ($rutasDeVisita as $ruta) {
            $rutas[$ruta->getId()] = $ruta;
        }
    }

    return array_values($rutas);
}

}
