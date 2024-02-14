<?php
// src/Controller/Api/RutaApi.php

namespace App\Controller\Api;
use DateTime;
use App\Entity\Ruta;
use App\Entity\Visita;
use App\Repository\RutaRepository;
use App\Repository\VisitaRepository;
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
public function insert(Request $request, EntityManagerInterface $entityManager, VisitaRepository $visitaRepository): JsonResponse
{
    // Obtener los datos del formulario
    $nombre = $request->request->get('nombre');
    $descripcion = $request->request->get('descripcion');
    $puntoInicio = $request->request->get('puntoInicio');
    $tamanoMaximo = $request->request->get('tamanoMaximo');
    $fechaInicio = DateTime::createFromFormat('d/m/Y', $request->request->get('fechaInicio'));
    $fechaFin = DateTime::createFromFormat('d/m/Y', $request->request->get('fechaFin'));
    $programacion = json_decode($request->request->get('programacion'));
    $ids = $data['visitasId'] ?? [];

    // Crear una nueva instancia de la entidad Ruta
    $ruta = new Ruta();
    $ruta->setNombre($nombre);
    $ruta->setDescripcion($descripcion);
    $ruta->setPuntoInicio($puntoInicio);
    $ruta->setTamanoMaximo($tamanoMaximo);
    $ruta->setFechaInicio($fechaInicio);
    $ruta->setFechaFin($fechaFin);
    $ruta->setProgramacion($programacion);

    // Manejar la carga de archivos
    $file = $request->files->get('foto');
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move(
            $this->getParameter('fotos_ruta'),
            $fileName
        );
        $ruta->setFoto($fileName);

    // Buscar y añadir las visitas relacionadas
    $visitasEncontradas = [];
    foreach ($ids as $id) {
        $visita = $visitaRepository->find($id);
        if ($visita) {
            $visitasEncontradas[] = $visita;
        }
    }
    foreach ($visitasEncontradas as $visita) {
        $ruta->addVisita($visita);
    }

    // Persistir y guardar la entidad Ruta en la base de datos
    $entityManager->persist($ruta);
    $entityManager->flush();

    // Devolver una respuesta JSON con el ID de la nueva entidad creada y el código de estado HTTP 201 (Created)
    return new JsonResponse(['id' => $ruta->getId()], JsonResponse::HTTP_CREATED);
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
