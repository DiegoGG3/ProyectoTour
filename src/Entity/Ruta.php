<?php

namespace App\Entity;

use App\Repository\RutaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?int $tamañoMaximo = null;

    #[ORM\OneToMany(mappedBy: 'codRuta', targetEntity: Tour::class, orphanRemoval: true)]
    private Collection $tours;

    #[ORM\OneToMany(mappedBy: 'codRuta', targetEntity: RutaVisitas::class)]
    private Collection $lugaresVisitados;

    public function __construct()
    {
        $this->tours = new ArrayCollection();
        $this->lugaresVisitados = new ArrayCollection();
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

    public function getTamañoMaximo(): ?int
    {
        return $this->tamañoMaximo;
    }

    public function setTamañoMaximo(int $tamañoMaximo): static
    {
        $this->tamañoMaximo = $tamañoMaximo;

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

    /**
     * @return Collection<int, RutaVisitas>
     */
    public function getLugaresVisitados(): Collection
    {
        return $this->lugaresVisitados;
    }

    public function addLugaresVisitado(RutaVisitas $lugaresVisitado): static
    {
        if (!$this->lugaresVisitados->contains($lugaresVisitado)) {
            $this->lugaresVisitados->add($lugaresVisitado);
            $lugaresVisitado->setCodRuta($this);
        }

        return $this;
    }

    public function removeLugaresVisitado(RutaVisitas $lugaresVisitado): static
    {
        if ($this->lugaresVisitados->removeElement($lugaresVisitado)) {
            // set the owning side to null (unless already changed)
            if ($lugaresVisitado->getCodRuta() === $this) {
                $lugaresVisitado->setCodRuta(null);
            }
        }

        return $this;
    }
}
