<?php
namespace App\Controller;

use App\Repository\ReservaRepository;
use App\Repository\TourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;

class FinalizarTourController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

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
    public function cancelarTour(ReservaRepository $reservaRepository, TourRepository $TourRepository, $Tour_id, EntityManagerInterface $entityManager): Response
    {
        if (!isset($Tour_id)) {
            throw new \InvalidArgumentException('Se requiere el parámetro Tour_id en la URL.');
        }

        $Tour = $TourRepository->find($Tour_id);

        if (!$Tour) {
            throw $this->createNotFoundException('No se encontró la Tour con el ID proporcionado.');
        }

        $reservas = $reservaRepository->findBy(['codTour' => $Tour_id]);

        foreach ($reservas as $reserva) {
            $usuario = $reserva->getCodUser();

            $correoUsuario = $usuario->getEmail();

            $fechaHora = $Tour->getFechaHora()->format('Y-m-d H:i:s'); 

            $textoCorreo = 'Se canceló el tour de la fecha ' . $fechaHora . '.'; 

            $email = (new Email())
                ->from('DiegoTours@example.com')
                ->to($correoUsuario)
                ->subject('Canceacion del tour')
                ->text($textoCorreo);

            $this->mailer->send($email);

            $entityManager->remove($reserva);

        }
        $entityManager->remove($Tour);
        $entityManager->flush();


        return new Response(
            '<script>alert("Tour cancelado con éxito");</script>',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }
    
}
