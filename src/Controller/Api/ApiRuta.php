<?php
// src/Controller/Api/RutaApi.php

namespace App\Controller\Api;
use DateTime;
use App\Entity\Ruta;
use App\Entity\Tour;
use App\Entity\User;
use App\Entity\Visita;
use App\Repository\RutaRepository;
use App\Repository\VisitaRepository;
use App\Repository\UserRepository;

use App\Service\RutaService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/apiRuta", name: "ruta-")]
class ApiRuta extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route("/findAll", name: "findAll", methods: ["GET"])]
    public function findAll(RutaRepository $rutaRepository): Response
    {
        $rutas = $rutaRepository->findAll();
        $data = $this->serializer->serialize($rutas, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route("/findById/{id}", name: "findById", methods: ["GET"])]
    public function findById($id, RutaRepository $rutaRepository): Response
    {
        $ruta = $rutaRepository->find($id);
        if (!$ruta) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = $this->serializer->serialize($ruta, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route("/insert", name: "insert", methods: ["POST"])]
    public function insert(Request $request, EntityManagerInterface $entityManager, VisitaRepository $visitaRepository, UserRepository $userRepository): JsonResponse
    {
        $nombre = $request->request->get('nombre');
        $descripcion = $request->request->get('descripcion');
        $puntoInicio = $request->request->get('puntoInicio');
        $tamanoMaximo = $request->request->get('tamanoMaximo');
        $fechaInicio = DateTime::createFromFormat('d/m/Y', $request->request->get('fechaInicio'));
        $fechaFin = DateTime::createFromFormat('d/m/Y', $request->request->get('fechaFin'));
        $programacion = json_decode($request->request->get('programacion'));
        $creaTour = $request->request->get('creaTour');
        $idGuia = $request->request->get('idGuia');

        
        $ids = $request->request->get('visitasId');
        $idsArray = array_map('intval', explode(',', $ids));

        
    
        $ruta = new Ruta();
        $ruta->setNombre($nombre);
        $ruta->setDescripcion($descripcion);
        $ruta->setPuntoInicio($puntoInicio);
        $ruta->setTamanoMaximo($tamanoMaximo);
        $ruta->setFechaInicio($fechaInicio);
        $ruta->setFechaFin($fechaFin);
        $ruta->setProgramacion($programacion);
    
        $file = $request->files->get('foto');
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move(
            $this->getParameter('fotos_ruta'),
            $fileName
        );
        $ruta->setFoto($fileName);
    
        if (is_iterable($idsArray)) {
            foreach ($idsArray as $id) {
                $visita = $visitaRepository->find($id);
                if ($visita) {
                    $ruta->addVisita($visita);
                }
            }
        } else {
            $ids = [];
        }
        
        $entityManager->persist($ruta);
        $entityManager->flush();

        foreach ($programacion as $programa) {
            // Crear un nuevo objeto Tour
            $tour = new Tour();
            $tour->setCodRuta($ruta); // Asignar la ruta asociada al tour
        
            // Supongamos que tienes un ID de guía disponible para cada programación de ruta
            // Puedes obtener el objeto User (guía) de la base de datos utilizando el UserRepository
            $guia = $userRepository->find($idGuia);
            $tour->setGuia($guia); // Asignar el guía al tour
        
            $tour->setFechaHora(new \DateTime()); // Establecer la fecha y hora actual para el tour
        
            // Persistir el tour en la base de datos
            $entityManager->persist($tour);
        }

        $entityManager->flush();


        return new JsonResponse(['id' => $creaTour], JsonResponse::HTTP_CREATED);
    }

       

    #[Route("/update/{id}", name: "update", methods: ["PUT"])]
    public function update(Request $request, $id, RutaRepository $rutaRepository): Response
    {
        $ruta = $rutaRepository->find($id);
        if (!$ruta) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = json_decode($request->getContent(), true);
        // Aquí puedes actualizar la entidad Ruta con los datos recibidos en $data
    }

    #[Route("/delete/{id}", name: "delete", methods: ["DELETE"])]
    public function delete($id, RutaRepository $rutaRepository): Response
    {
        $entityManager = $rutaRepository->getManager();
        $ruta = $entityManager->getRepository(Ruta::class)->find($id);
        if (!$ruta) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($ruta);
        $entityManager->flush();
        return new Response(null, Response::HTTP_OK);
    }
}
