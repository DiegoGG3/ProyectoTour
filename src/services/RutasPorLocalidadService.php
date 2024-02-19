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

    // Obtener las visitas asociadas a la localidad
    $visitas = $localidad->getVisitas();

    // Recorrer cada visita y obtener las rutas asociadas
    foreach ($visitas as $visita) {
        $rutasDeVisita = $visita->getRutas()->toArray();
        foreach ($rutasDeVisita as $ruta) {
            // Usar el ID de la ruta como clave para evitar duplicados
            $rutas[$ruta->getId()] = $ruta;
        }
    }

    return array_values($rutas); // Convertir el array asociativo en un array indexado
}

}
