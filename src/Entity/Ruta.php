<?php

namespace App\Entity;

use App\Repository\RutaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: RutaRepository::class)]
#[Broadcast]
class Ruta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255)]
    private ?string $foto = null;

    #[ORM\Column(length: 255)]
    private ?string $puntoInicio = null;

    #[ORM\Column]
    private ?int $tamanoMaximo = null;

    #[ORM\OneToMany(mappedBy: 'codRuta', targetEntity: Tour::class, orphanRemoval: true)]
    private Collection $tours;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaInicio = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaFin = null;

    #[ORM\Column]
    private array $programacion = [];

    #[ORM\ManyToMany(targetEntity: Visita::class, inversedBy: 'rutas')]
    private Collection $visitas;

    public function __construct()
    {
        $this->tours = new ArrayCollection();
        $this->visitas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(string $foto): static
    {
        $this->foto = $foto;

        return $this;
    }

    public function getPuntoInicio(): ?string
    {
        return $this->puntoInicio;
    }

    public function setPuntoInicio(string $puntoInicio): static
    {
        $this->puntoInicio = $puntoInicio;

        return $this;
    }

    public function getTamanoMaximo(): ?int
    {
        return $this->tamanoMaximo;
    }

    public function setTamanoMaximo(int $tamanoMaximo): static
    {
        $this->tamanoMaximo = $tamanoMaximo;

        return $this;
    }

    /**
     * @return Collection<int, Tour>
     */
    public function getTours(): Collection
    {
        return $this->tours;
    }

    public function addTour(Tour $tour): static
    {
        if (!$this->tours->contains($tour)) {
            $this->tours->add($tour);
            $tour->setCodRuta($this);
        }

        return $this;
    }

    public function removeTour(Tour $tour): static
    {
        if ($this->tours->removeElement($tour)) {
            // set the owning side to null (unless already changed)
            if ($tour->getCodRuta() === $this) {
                $tour->setCodRuta(null);
            }
        }

        return $this;
    }


    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTimeInterface $fechaInicio): static
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fechaFin;
    }

    public function setFechaFin(\DateTimeInterface $fechaFin): static
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    public function getProgramacion(): array
    {
        return $this->programacion;
    }

    public function setProgramacion(array $programacion): static
    {
        $this->programacion = $programacion;

        return $this;
    }

    /**
     * @return Collection<int, visita>
     */
    public function getVisitas(): Collection
    {
        return $this->visitas;
    }

    public function addVisita(Visita $visita): static
    {
        if (!$this->visitas->contains($visita)) {
            $this->visitas->add($visita);
        }

        return $this;
    }

    public function removeVisita(Visita $visita): static
    {
        $this->visitas->removeElement($visita);

        return $this;
    }
}
