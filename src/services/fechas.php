<?php
namespace App\Service;

use App\Entity\Ruta;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use DateInterval;
use App\Entity\Tour;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class crearTour
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function crear(Ruta $ruta,int $idGuia): Response
    {
        $diasSemana = array(
            '1' => 'L',
            '2' => 'M',
            '3' => 'X',
            '4' => 'J',
            '5' => 'V',
            '6' => 'S',
            '7' => 'D'
        );

        foreach ($ruta->getProgramacion() as $programacion) {
            $rangoFechas = explode(" - ", $programacion->rangoFecha); 
            $fecha = DateTime::createFromFormat('d/m/Y', str_replace('/', '/', $rangoFechas[0]));
            $fechaFin = DateTime::createFromFormat('d/m/Y', str_replace('/', '/', $rangoFechas[1]));
            $intervalo = new DateInterval('P1D'); 
            $dias = explode(",", $programacion->dias); 

            while ($fecha <= $fechaFin) {
                if (array_search($diasSemana[$fecha->format('N')], $dias) !== false) {
                    $tour = new Tour();
                    $tour->setCodRuta($ruta);
                    $fechaHora = DateTime::createFromFormat('Y-m-d H:i', $fecha->format('Y-m-d') . " " . $programacion->hora);
                    $tour->setFechaHora($fechaHora);
                    $tour->setGuia($this->entityManager->getRepository(User::class)->findById($idGuia)[0]);
                    $tour->setFinalizado(false);
                    $this->entityManager->persist($tour);
                }
                $fecha->add($intervalo);
            }
            $this->entityManager->flush();
        }

        return new Response("Tours generados");
    }
}