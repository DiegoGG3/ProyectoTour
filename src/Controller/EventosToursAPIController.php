<?php

namespace App\Controller;

use App\Entity\Ruta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Tour;
use App\Repository\RutaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventosToursAPIController extends AbstractController
{
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    #[Route('/eventosToursAPI', name: 'app_eventos_tours_a_p_i')]
    public function index(): JsonResponse
    {
        $tours = $this->entityManager->getRepository(Tour::class)->findAll();

        $serializedTours = array_map(function($tour) {
            return $this->serializeTour($tour, 'Level::BASIC');
        }, $tours);

        return new JsonResponse($serializedTours);
    }

    public function serializeTour(Tour $tour, $level=""): array
    {

        switch ($level) {
            case 'Level::BASIC':
                return [
                    'id' => $tour->getId(),
                    'guia' => $tour->getGuia(),
                    'finalizado' => $tour->isFinalizado(),
                    'fechaHora' => $tour->getFechaHora()->format('Y-m-d H:i:s'), 
                    'codRuta' => $tour->getCodRuta()->getId(), 
                    'nombre' => $tour->getCodRuta()->getNombre(), 
                ];
                break;
            default:
                return ["result" => "TO IMPLEMENT"];
                break;
        }
    }

    #[Route('/rutaIdAPI', name: 'app_ruta_id_api')]
public function rutaIdAPI(Request $request, EntityManagerInterface $entityManager, RutaRepository $rutaRepository): JsonResponse
{
    $idTour = $request->query->get('idRuta');
    $tour = $entityManager->getRepository(Tour::class)->find($idTour); 

    if (!$tour) {
        return new JsonResponse(['error' => 'Tour no encontrado'], 404);
    }

    $ruta = $tour->getCodRuta();
    if (!$ruta) {
        return new JsonResponse(['error' => 'Ruta no encontrada'], 404);
    }

    $rutaData = [
        'id' => $ruta->getId(),
        'nombre' => $ruta->getNombre(),
    ];

    return new JsonResponse($rutaData);
}

}