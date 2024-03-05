<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Tour;
use App\Entity\User;


class EditarGuiaAPIController extends AbstractController
{
    #[Route('/editarGuiaAPI', name: 'app_editar_guia_a_p_i')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $emailGuia = $request->request->get('emailGuia');
        $idTour = $request->request->get('idTour');

        $tour=$entityManagerInterface->getRepository(Tour::class)->find($idTour);

        $tour->setGuia($entityManagerInterface->getRepository(User::class)->findBy(['email' => $emailGuia])[0]);

        $entityManagerInterface->persist($tour);
        $entityManagerInterface->flush();

        return new Response("TODO OK");
    }
}