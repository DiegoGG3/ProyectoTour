<?php

namespace App\Entity;

use App\Repository\ValoracionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ValoracionRepository::class)]
#[Broadcast]
class Valoracion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $notaGuia = null;

    #[ORM\Column]
    private ?int $notaRuta = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comentario = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reserva $codReserva = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotaGuia(): ?int
    {
        return $this->notaGuia;
    }

    public function setNotaGuia(int $notaGuia): static
    {
        $this->notaGuia = $notaGuia;

        return $this;
    }

    public function getNotaRuta(): ?int
    {
        return $this->notaRuta;
    }

    public function setNotaRuta(int $notaRuta): static
    {
        $this->notaRuta = $notaRuta;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(?string $comentario): static
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getCodReserva(): ?Reserva
    {
        return $this->codReserva;
    }

    public function setCodReserva(Reserva $codReserva): static
    {
        $this->codReserva = $codReserva;

        return $this;
    }
}
