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
    private ?Ruta $codRuta = null;

    #[ORM\ManyToOne(inversedBy: 'rutaVisitas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Visita $codVisita = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodRuta(): ?Ruta
    {
        return $this->codRuta;
    }

    public function setCodRuta(?Ruta $codRuta): static
    {
        $this->codRuta = $codRuta;

        return $this;
    }

    public function getCodVisita(): ?Visita
    {
        return $this->codVisita;
    }

    public function setCodVisita(?Visita $codVisita): static
    {
        $this->codVisita = $codVisita;

        return $this;
    }
}
