<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarioController extends AbstractController
{
    #[Route('/calendario', name: 'calendario')]
    public function index(): Response
    {
        return $this->render('calendario.html.twig');
    }
}
