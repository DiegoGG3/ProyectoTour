<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;


#[Route("/ApiUser", name: "user-")]

class ApiUser extends AbstractController
{
    #[Route('/insert', name: 'insert', methods: ['POST'])]
    public function guardarUsuario(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $nombre = $request->request->get('nombre');
        $roles = ['ROLE_USER']; 
        $file = $request->files->get('foto');

        $usuario = new User();
        $usuario->setEmail($email);
        $usuario->setPassword(password_hash($password, PASSWORD_BCRYPT)); 
        $usuario->setNombre($nombre);
        $usuario->setRoles($roles);
        
        $filename = md5(uniqid()).'.'.$file->guessExtension();
        
        $file->move(
            $this->getParameter('fotos_perfil'),
            $filename
        );
        $usuario->setFoto($filename);

        $entityManager->persist($usuario);
        $entityManager->flush();
        
        return $this->redirectToRoute('home');

        return $this->json(['message' => 'Usuario creado exitosamente'], JsonResponse::HTTP_CREATED);
    }
}
