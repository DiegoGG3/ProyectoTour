<?php
namespace App\Controller;

use App\Entity\Ruta;
use App\Repository\RutaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormularioReservaController extends AbstractController
{
    #[Route('/formulario_reserva/{Ruta_id}', name:"formulario_reserva")]

    public function formularioReserva(RutaRepository $RutaRepository, $Ruta_id): Response
{
    if (!isset($Ruta_id)) {
        throw new \InvalidArgumentException('Se requiere el parámetro Ruta_id en la URL.');
    }

    $Ruta = $RutaRepository->find($Ruta_id);

    if (!$Ruta) {
        throw $this->createNotFoundException('No se encontró la ruta con el ID proporcionado.');
    }

    $toursPorRuta = $Ruta->getTours(); 

    return $this->render('formulario_reserva.html.twig', [
        'toursPorRuta' => $toursPorRuta,
    ]);
}

}
