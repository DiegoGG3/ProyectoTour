<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
#[Broadcast]
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaReserva = null;

    #[ORM\Column]
    private ?int $genteReservada = null;

    #[ORM\Column(nullable: true)]
    private ?int $genteAsistente = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tour $codTour = null;

    #[ORM\ManyToOne(inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $codUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaReserva(): ?\DateTimeInterface
    {
        return $this->fechaReserva;
    }

    public function setFechaReserva(\DateTimeInterface $fechaReserva): static
    {
        $this->fechaReserva = $fechaReserva;

        return $this;
    }

    public function getGenteReservada(): ?int
    {
        return $this->genteReservada;
    }

    public function setGenteReservada(int $genteReservada): static
    {
        $this->genteReservada = $genteReservada;

        return $this;
    }

    public function getGenteAsistente(): ?int
    {
        return $this->genteAsistente;
    }

    public function setGenteAsistente(?int $genteAsistente): static
    {
        $this->genteAsistente = $genteAsistente;

        return $this;
    }

    public function getCodTour(): ?Tour
    {
        return $this->codTour;
    }

    public function setCodTour(?Tour $codTour): static
    {
        $this->codTour = $codTour;

        return $this;
    }

    public function getCodUser(): ?user
    {
        return $this->codUser;
    }

    public function setCodUser(?user $codUser): static
    {
        $this->codUser = $codUser;

        return $this;
    }
}
