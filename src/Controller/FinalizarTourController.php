<?php
namespace App\Controller;

use App\Repository\ReservaRepository;
use App\Repository\TourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;

class FinalizarTourController extends AbstractController
{
    #[Route('/FinalizarTour/{Tour_id}', name:"FinalizarTour")]
    public function formularioReserva(TourRepository $TourRepository, $Tour_id ,ReservaRepository $ReservaRepository): Response
    {
        if (!isset($Tour_id)) {
            throw new \InvalidArgumentException('Se requiere el parámetro Tour_id en la URL.');
        }

        $Tour = $TourRepository->find($Tour_id);
        $reservas = $ReservaRepository->findBy(['codTour' => $Tour_id]);

        if (!$Tour) {
            throw $this->createNotFoundException('No se encontró la Tour con el ID proporcionado.');
        }


        return $this->render('formulario_tour.html.twig', [
            'tour' => $Tour,
            'reservas' => $reservas
        ]);
    }

    #[Route('/CancelarTour/{Tour_id}', name: "CancelarTour")]
    public function cancelarTour(TourRepository $TourRepository, $Tour_id, EntityManagerInterface $entityManager, SessionInterface $session): JsonResponse
    {
        if (!isset($Tour_id)) {
            throw new \InvalidArgumentException('Se requiere el parámetro Tour_id en la URL.');
        }

        $Tour = $TourRepository->find($Tour_id);

        if (!$Tour) {
            throw $this->createNotFoundException('No se encontró la Tour con el ID proporcionado.');
        }

        // Eliminar el tour de la base de datos
        $entityManager->remove($Tour);
        $entityManager->flush();

        // Establecer la bandera de cancelación en la sesión
        // $session->set('tour_cancelado', true);

        return new JsonResponse(['message' => 'Tour eliminado con éxito'], JsonResponse::HTTP_OK);
    }
    
}
