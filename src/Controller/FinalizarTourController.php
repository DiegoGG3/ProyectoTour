<?php
namespace App\Controller;

use App\Repository\ReservaRepository;
use App\Repository\TourRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

}
