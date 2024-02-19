<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Tour;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReservaController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/crear_reserva', name: 'crear_reserva')]

    public function crearReserva(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Verificar si los datos están presentes y si el campo tour_id está definido
        if (!$data || !isset($data['tour_id'])) {
            return new JsonResponse(['error' => 'Datos de reserva incorrectos'], 400);
        }

        $tourId = $data['tour_id'];

        // Obtener el tour desde la base de datos
        $tour = $this->entityManager->getRepository(Tour::class)->find($tourId);

        // Verificar si el tour existe
        if (!$tour) {
            return new JsonResponse(['error' => 'El tour no existe'], 404);
        }

        // Crear una nueva reserva
        $reserva = new Reserva();
        $reserva->setCodTour($tour);
        // También puedes establecer otros campos de la reserva aquí si es necesario

        // Persistir la reserva en la base de datos
        $this->entityManager->persist($reserva);
        $this->entityManager->flush();

        return new JsonResponse(['mensaje' => 'Reserva creada con éxito'], 201);
    }
}
