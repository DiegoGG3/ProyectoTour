<?php

namespace App\Controller;

use App\Entity\Localidad;
use App\Entity\User;
use App\Entity\Visita;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class CrearRutaController extends AbstractController
{
    #[Route('/crear/ruta', name: 'app_crear_ruta')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Obtener todas las visitas de la base de datos
        $visitas = $entityManager->getRepository(Visita::class)->findAll();
        $localidades = $entityManager->getRepository(Localidad::class)->findAll();
        // En tu controlador u otro lugar donde necesites buscar usuarios con el rol de guía
        $guias = $entityManager->getRepository(User::class)->findByRoles();

        // Pasar las visitas a la plantilla Twig
        return $this->render('crear_ruta/index.html.twig', [
            'controller_name' => 'CrearRutaController',
            'visitas' => $visitas,
            'localidades' => $localidades,
            'guias' => $guias,
        ]);
    }

    #[Route("/obtener_lugares_disponibles", name: "obtener_lugares_disponibles")]
    public function obtenerLugaresDisponibles(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $localidadId = $request->request->get('localidadId');

        // Obtener los lugares disponibles según la localidad seleccionada
        $lugaresDisponibles = $entityManager->getRepository(Visita::class)->findBy(['localidad' => $localidadId]);

        // Convertir los resultados a un array de datos que se pueden serializar fácilmente
        $data = [];
        foreach ($lugaresDisponibles as $visita) {
            $data[] = [
                'id' => $visita->getId(),
                'nombre' => $visita->getNombre(),
                'foto' => $visita->getFoto(),

                // Agrega más campos según sea necesario
            ];
        }

        // Devolver los datos como respuesta JSON
        return new JsonResponse($data);
    }
}
