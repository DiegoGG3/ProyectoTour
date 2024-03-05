<?php
// src/Controller/Api/RutaApi.php

namespace App\Controller\Api;

use App\Entity\Localidad;
use DateTime;
use App\Entity\Ruta;
use App\Entity\Tour;
use App\Entity\User;
use App\Entity\Visita;
use App\Repository\RutaRepository;
use App\Repository\VisitaRepository;
use App\Repository\UserRepository;
use App\Service\crearTour;
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
    public function insert(Request $request, EntityManagerInterface $entityManager, VisitaRepository $visitaRepository, UserRepository $userRepository, crearTour $crearTour): JsonResponse
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

        $crearTour->crear($ruta, $idGuia);
        $entityManager->flush();

        return new JsonResponse(['id' => $creaTour], JsonResponse::HTTP_CREATED);
    }

    #[Route("/editar/{id}", name: "editar_ruta_bd", methods: ["POST"])]
    public function editar(Request $request, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $ruta = $entityManager->getRepository(Ruta::class)->find($id);

        if (!$ruta) {
            return new JsonResponse(['error' => 'Ruta no encontrada'], JsonResponse::HTTP_NOT_FOUND);
        }

        $nombre = $request->request->get('nombre');
        $descripcion = $request->request->get('descripcion');
        $puntoInicio = $request->request->get('puntoInicio');
        $tamanoMaximo = $request->request->get('tamanoMaximo');
        $fechaInicio = DateTime::createFromFormat('d/m/Y', $request->request->get('fechaInicio'));
        $fechaFin = DateTime::createFromFormat('d/m/Y', $request->request->get('fechaFin'));
        $programacion = json_decode($request->request->get('programacion'));
        $ids = $request->request->get('visitasId');
        $idsArray = array_map('intval', explode(',', $ids));

        $ruta->setNombre($nombre);
        $ruta->setDescripcion($descripcion);
        $ruta->setPuntoInicio($puntoInicio);
        $ruta->setTamanoMaximo($tamanoMaximo);
        $ruta->setFechaInicio($fechaInicio);
        $ruta->setFechaFin($fechaFin);
        $ruta->setProgramacion($programacion);

        $file = $request->files->get('foto');
        if ($file) {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('fotos_ruta'), $fileName);
            $ruta->setFoto($fileName);
        }

        if (is_iterable($idsArray)) {
            $ruta->getVisitas()->clear(); 
            foreach ($idsArray as $id) {
                $visita = $entityManager->getRepository(Visita::class)->find($id);
                if ($visita) {
                    $ruta->addVisita($visita);
                }
            }
        } else {
            $idsArray = [];
        }

        $entityManager->flush();

        return new JsonResponse(['success' => 'Ruta actualizada', 'id' => $ruta->getId()]);
    }


    #[Route("/ruta/editar/{id}", name: "editar_ruta")]
    public function editarRuta($id,  RutaRepository $rutaRepository, EntityManagerInterface $entityManager): Response
    {
        $ruta = $rutaRepository->find($id);
        $visitas = $entityManager->getRepository(Visita::class)->findAll();
        $localidades = $entityManager->getRepository(Localidad::class)->findAll();
        $guias = $entityManager->getRepository(User::class)->findByRoles();
    
        return $this->render('editar.html.twig', [
            'ruta' => $ruta,
            'visitas' => $visitas,
            'localidades' => $localidades,
            'guias' => $guias,
        ]);
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
