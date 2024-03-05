<?php
namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Tour;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\ReservaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserTour extends AbstractController
{
    #[Route('/tours_user/{idUser}', name: 'tours_user')]
    public function index(EntityManagerInterface $entityManager, $idUser): Response
    {
        $user = $entityManager->getRepository(User::class)->find($idUser);
        $reservas = $entityManager->getRepository(Reserva::class)->findBy(['codUser' => $user]);

        return $this->render('user_tours.html.twig', [
            'reservas' => $reservas,
        ]);
    }

    #[Route('/CancelarReserva/{Reserva_id}', name: "CancelarReserva")]
    public function cancelarReserva(ReservaRepository $ReservaRepository, $Reserva_id, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!isset($Reserva_id)) {
            throw new \InvalidArgumentException('Se requiere el parámetro Reserva_id en la URL.');
        }

        $Reserva = $ReservaRepository->find($Reserva_id);

        if (!$Reserva) {
            throw $this->createNotFoundException('No se encontró la Tour con el ID proporcionado.');
        }

        $entityManager->remove($Reserva);
        $entityManager->flush();


        return new JsonResponse(['message' => 'Tour eliminado con éxito'], JsonResponse::HTTP_OK);
    }
   


    #[Route('/ValorarTour/{Reserva_id}', name: "ValorarTour")]
    public function ValorarTour(ReservaRepository $ReservaRepository, $Reserva_id): Response
    {
        if (!isset($Reserva_id)) {
            throw new \InvalidArgumentException('Se requiere el parámetro Reserva_id en la URL.');
        }

        $Reserva = $ReservaRepository->find($Reserva_id);

        if (!$Reserva) {
            throw $this->createNotFoundException('No se encontró la reserva con el ID proporcionado.');
        }


        return $this->render('valoracion.html.twig', [
            'Reserva' => $Reserva,
        ]);
        
    }
}

