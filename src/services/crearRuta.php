<?php
namespace App\Service;

use App\Entity\Ruta;
use App\Entity\Tour;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class crearRuta
{
    public function crearRuta(Ruta $ruta, $idGuia,  UserRepository $userRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $programacion=$ruta->getProgramacion();
        foreach ($programacion as $programa) {
            $tour = new Tour();
            $tour->setCodRuta($ruta); 
            $guia = $userRepository->find($idGuia);
            $tour->setGuia($guia);
        
            $tour->setFechaHora(new \DateTime()); 
            $entityManager->persist($tour);
        }

        $entityManager->flush();
  
        return new JsonResponse(['id' => $tour], JsonResponse::HTTP_CREATED);
    }

}