<?php

namespace App\Controller\Api;

use App\Entity\Reserva;
use App\Entity\Valoracion;
use App\Repository\ValoracionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/apiValoracion", name: "valoracion-")]
class ApiValoracion extends AbstractController
{
    #[Route('/guardar', name: 'guardar_valoracion', methods: ['POST'])]
    public function guardarValoracion(Request $request, EntityManagerInterface $entityManager, ValoracionRepository $valoracionRepository): JsonResponse
    {
        $reservaId = $request->request->get('reservaId');
        $notaGuia = $request->request->get('notaGuia');
        $notaRuta = $request->request->get('notaRuta');
        $comentario = $request->request->get('comentario');

        $reserva = $entityManager->getRepository(Reserva::class)->find($reservaId);

        $valoracion = new Valoracion();
        $valoracion->setNotaGuia($notaGuia);
        $valoracion->setNotaRuta($notaRuta);
        $valoracion->setComentario($comentario);
        $valoracion->setCodReserva($reserva);

        $entityManager->persist($valoracion);
        $entityManager->flush();

        return $this->json(['message' => 'Valoración guardada exitosamente'], JsonResponse::HTTP_CREATED);
    }
}
