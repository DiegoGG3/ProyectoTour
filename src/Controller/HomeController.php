<?php
namespace App\Controller;

use App\Entity\Localidad;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class HomeController extends AbstractController
{

    #[Route('', name:"home")]
    public function home(EntityManagerInterface $entityManager): Response
    {
        $localidades = $entityManager->getRepository(Localidad::class)->findAll();

        return $this->render('home/index.html.twig', [
            'localidades' => $localidades,
        ]);
    } 
    
}
?>