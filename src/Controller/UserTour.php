<?php
namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Ruta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tour;
use App\Entity\User;

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
}

