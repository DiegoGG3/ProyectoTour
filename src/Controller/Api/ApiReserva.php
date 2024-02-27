<?php

namespace App\Controller\Api;

use App\Entity\Reserva;
use App\Entity\Tour;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
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
        $idTour = $request->request->get('idTour');
        $idUser = $request->request->get('idUser');
        $genteReservada = $request->request->get('gente');

        $tour = $entityManager->getRepository(Tour::class)->findBy(['id' => $idTour]);
        $userId = $entityManager->getRepository(User::class)->findBy(['id' => $idUser]);
        $fechaHora = new DateTime();

        $reserva = new Reserva();
        $reserva->setCodTour($tour[0]);
        $reserva->setGenteReservada($genteReservada);
        $reserva->setCodUser($userId[0]); 
        $reserva->setFechaReserva($fechaHora); 
        $reserva->setGenteAsistente(0);

        $this->entityManager->persist($reserva);
        $this->entityManager->flush();

        return new JsonResponse(['id' => $reserva->getId()], JsonResponse::HTTP_CREATED);
        return new JsonResponse(['error' => 'Usuario no autenticado'], JsonResponse::HTTP_UNAUTHORIZED);
    }

    #[Route("/edit", name: "edit", methods: ["POST"])]
public function edit(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $reservas = $request->request->get('idReservas');
    $idReservas = json_decode($reservas); // Decodificar las IDs de reserva desde JSON
    foreach ($idReservas as $idReserva) {
        $cantidad = $request->request->get('asistente_' . $idReserva);
        $reserva = $entityManager->getRepository(Reserva::class)->find($idReserva);

        $reserva->setGenteAsistente($cantidad);

        $this->entityManager->persist($reserva);
    }

    
    $entityManager->flush();

    return new JsonResponse(['message' => 'Reservas actualizadas con éxito'], JsonResponse::HTTP_OK);
}


}
