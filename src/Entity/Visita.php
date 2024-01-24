<?php

namespace App\Entity;

use App\Repository\VisitaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: VisitaRepository::class)]
#[Broadcast]
class Visita
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
    private ?string $coordenadas = null;

    #[ORM\ManyToOne(inversedBy: 'visitas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Localidad $localidad = null;

    #[ORM\OneToMany(mappedBy: 'codVisita', targetEntity: RutaVisitas::class)]
    private Collection $rutaVisitas;

    public function __construct()
    {
        $this->rutaVisitas = new ArrayCollection();
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

    public function getCoordenadas(): ?string
    {
        return $this->coordenadas;
    }

    public function setCoordenadas(string $coordenadas): static
    {
        $this->coordenadas = $coordenadas;

        return $this;
    }

    public function getLocalidad(): ?Localidad
    {
        return $this->localidad;
    }

    public function setLocalidad(?Localidad $localidad): static
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * @return Collection<int, RutaVisitas>
     */
    public function getRutaVisitas(): Collection
    {
        return $this->rutaVisitas;
    }

    public function addRutaVisita(RutaVisitas $rutaVisita): static
    {
        if (!$this->rutaVisitas->contains($rutaVisita)) {
            $this->rutaVisitas->add($rutaVisita);
            $rutaVisita->setCodVisita($this);
        }

        return $this;
    }

    public function removeRutaVisita(RutaVisitas $rutaVisita): static
    {
        if ($this->rutaVisitas->removeElement($rutaVisita)) {
            // set the owning side to null (unless already changed)
            if ($rutaVisita->getCodVisita() === $this) {
                $rutaVisita->setCodVisita(null);
            }
        }

        return $this;
    }
}
