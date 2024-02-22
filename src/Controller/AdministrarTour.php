<?php
namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tour; 

class AdministrarTour extends AbstractController
{
    #[Route('/administrar_ruta/{idGuia}', name: 'administrar_ruta')]
    public function index(EntityManagerInterface $entityManager, $idGuia): Response
    {
        $tours = $entityManager->getRepository(Tour::class)->findBy(['guia_id' => $idGuia]);

        return $this->render('crear_ruta/index.html.twig', [
            'controller_name' => 'administrar_tours',
            'tours' => $tours
        ]);
    }
}

