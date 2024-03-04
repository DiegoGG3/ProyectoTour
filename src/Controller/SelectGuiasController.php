<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

class SelectGuiasController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/selectGuias', name: 'app_select_guias')]
    public function index(EntityManagerInterface $entityManagerInterface): JsonResponse
    {
        // Obtener todos los guías (usuarios con el rol ROLE_GUIDE)
        $guias = $entityManagerInterface->getRepository(User::class)->findByRoles(['ROLE_GUIDE']);

        // Serializar los guías
        $serializedGuias = array_map(function ($guia) {
            return $this->serializeGuia($guia, 'Level::BASIC');
        }, $guias);

        // Devolver la respuesta JSON
        return new JsonResponse($serializedGuias);
    }

    // Función de serialización personalizada para los guías (users)
    public function serializeGuia(User $guia, $level = ""): array
    {
        switch ($level) {
            case 'Level::BASIC':
                return [
                    'id' => $guia->getId(),
                    'email' => $guia->getEmail(),
                    'roles' => $guia->getRoles(),
                    'password' => $guia->getPassword(),
                    'nombre' => $guia->getNombre(),
                    'foto' => $guia->getFoto(),
                ];
                break;
            default:
                return ["result" => "TO IMPLEMENT"];
                break;
        }
    }
}
