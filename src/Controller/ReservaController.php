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

        if (!$data || !isset($data['tour_id'])) {
            return new JsonResponse(['error' => 'Datos de reserva incorrectos'], 400);
        }

        $tourId = $data['tour_id'];

        $tour = $this->entityManager->getRepository(Tour::class)->find($tourId);

        if (!$tour) {
            return new JsonResponse(['error' => 'El tour no existe'], 404);
        }

        $reserva = new Reserva();
        $reserva->setCodTour($tour);

        $this->entityManager->persist($reserva);
        $this->entityManager->flush();

        return new JsonResponse(['mensaje' => 'Reserva creada con éxito'], 201);
    }
}
