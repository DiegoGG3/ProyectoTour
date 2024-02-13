<?php
// src/Controller/Api/RutaApi.php

namespace App\Controller\Api;

use App\Entity\Ruta;
use App\Entity\Visita;
use App\Repository\RutaRepository;
use App\Service\RutaService;
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
    public function insert(Request $request): JsonResponse
    {
        // Obtener los datos en formato JSON
        $data = json_decode($request->getContent(), true);
        $ruta = new Ruta;
        $ruta->setNombre($data['nombre'] ?? null);
        $ruta->setDescripcion($data['descripcion'] ?? null);
        $ruta->setFoto($data['foto'] ?? null);
        $ruta->setPuntoInicio($data['puntoInicio'] ?? null);
        $ruta->setTamanoMaximo($data['tamanoMaximo'] ?? null);

        $ruta->addVisita($data['visitas'] ?? null);
        // Devuelve una respuesta JSON con el ID de la nueva entidad creada y el código de estado HTTP 201 (Created)
        return new JsonResponse(['id' => $ruta], JsonResponse::HTTP_CREATED);
        
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
