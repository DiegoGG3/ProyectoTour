<?php

namespace App\Controller\Api;

use App\Entity\Reserva;
use App\Entity\Tour;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

#[Route("/ApiReserva", name: "reserva-")]
class ApiReserva extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    #[Route("/insert", name: "insert", methods: ["POST"])]
    public function insert(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Obtener el ID del tour y la cantidad de personas de la solicitud
        $idTour = $request->request->get('idTour');
        $genteReservada = $request->request->get('gente');

        // Obtener el usuario autenticado

        // Verificar si el usuario está autenticado
        $tour = $entityManager->getRepository(Tour::class)->findBy(['id' => $idTour]);

        $userId = $entityManager->getRepository(User::class)->findBy(['id' => 5]);
         
            $fechaHora = new DateTime();

            // Crear la reserva
            $reserva = new Reserva();
            $reserva->setCodTour($tour[0]);
            $reserva->setGenteReservada($genteReservada);
            $reserva->setCodUser($userId[0]); // Establecer el ID del usuario
            $reserva->setFechaReserva($fechaHora); // Establecer la fecha y hora actual
            $reserva->setGenteAsistente(0);

            // Persistir y guardar la reserva
            $this->entityManager->persist($reserva);
            $this->entityManager->flush();

            // Devolver la respuesta
            return new JsonResponse(['id' => $reserva->getId()], JsonResponse::HTTP_CREATED);
        

        // Si el usuario no está autenticado, devolver una respuesta de error
        return new JsonResponse(['error' => 'Usuario no autenticado'], JsonResponse::HTTP_UNAUTHORIZED);
    }
}
