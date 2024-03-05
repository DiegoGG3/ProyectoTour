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
        $visitas = $entityManager->getRepository(Visita::class)->findAll();
        $localidades = $entityManager->getRepository(Localidad::class)->findAll();
        $guias = $entityManager->getRepository(User::class)->findByRoles();

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

        $lugaresDisponibles = $entityManager->getRepository(Visita::class)->findBy(['localidad' => $localidadId]);

        $data = [];
        foreach ($lugaresDisponibles as $visita) {
            $data[] = [
                'id' => $visita->getId(),
                'nombre' => $visita->getNombre(),
                'foto' => $visita->getFoto(),

            ];
        }

        return new JsonResponse($data);
    }
}
