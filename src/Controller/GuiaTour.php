<?php
namespace App\Controller;

use App\Entity\Ruta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tour;
use App\Entity\User;

class GuiaTour extends AbstractController
{
    #[Route('/guia_ruta/{idGuia}', name: 'guia_ruta')]
    public function index(EntityManagerInterface $entityManager, $idGuia): Response
    {
        $guia = $entityManager->getRepository(User::class)->findBy(['id' => $idGuia]);
        $tours = $entityManager->getRepository(Tour::class)->findBy(['guia' => $guia]);


        return $this->render('guia_tours.html.twig', [
            'tours' => $tours,
        ]);
    }
}

