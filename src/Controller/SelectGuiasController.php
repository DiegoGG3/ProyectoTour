<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;


class SelectGuiasController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('/selectGuias', name: 'app_select_guias')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        $guias=$entityManagerInterface->getRepository(User::class)->findByRoles(['ROLE_GUIDE']);

        $serializedGuias = $this->serializer->serialize($guias, 'json');

        return new Response($serializedGuias);
    }
}