<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;

class PdfGeneratorController extends AbstractController
{
    #[Route('/pdf/generator', name: 'app_pdf_generator')]
    public function index(): Response
    {
        $data = [
            'imageSrc2'      => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/fotos_perfil/17b426c003ab366f8b6394c95c5a2d1e.png'),
            'imageSrc'      => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/fotos_perfil/lambo.gif'),
            'name'          => 'Diegogg',
            'address'       => 'Villardompardo',
            'mobileNumber'  => '1112222',
            'email'         => 'diego@email.com'
        ];

        $html = $this->renderView('pdf_generator/index.html.twig', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="resume.pdf"',
            ]
        );
    }

    private function imageToBase64($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
