<?php

namespace App\Entity;

use App\Repository\RutaVisitasRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: RutaVisitasRepository::class)]
#[Broadcast]
class RutaVisitas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lugaresVisitados')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ruta $codRuta = null;

    #[ORM\ManyToOne(inversedBy: 'rutaVisitas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?visita $codVisita = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodRuta(): ?ruta
    {
        return $this->codRuta;
    }

    public function setCodRuta(?ruta $codRuta): static
    {
        $this->codRuta = $codRuta;

        return $this;
    }

    public function getCodVisita(): ?visita
    {
        return $this->codVisita;
    }

    public function setCodVisita(?visita $codVisita): static
    {
        $this->codVisita = $codVisita;

        return $this;
    }
}
