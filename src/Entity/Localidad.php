<?php

namespace App\Entity;

use App\Repository\LocalidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: LocalidadRepository::class)]
#[Broadcast]
class Localidad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'localidades')]
    #[ORM\JoinColumn(name: 'cod_provincia_id', referencedColumnName: 'id', nullable: false)]
    private ?Provincia $codProvincia = null;



    #[ORM\OneToMany(mappedBy: 'localidad', targetEntity: Visita::class)]
    private Collection $visitas;

    public function __construct()
    {
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

    public function getCodProvincia(): ?Provincia
    {
        return $this->codProvincia;
    }

    public function setCodProvincia(?Provincia $codProvincia): static
    {
        $this->codProvincia = $codProvincia;

        return $this;
    }

    /**
     * @return Collection<int, Visita>
     */
    public function getVisitas(): Collection
    {
        return $this->visitas;
    }

    public function addVisita(Visita $visita): static
    {
        if (!$this->visitas->contains($visita)) {
            $this->visitas->add($visita);
            $visita->setLocalidad($this);
        }

        return $this;
    }

    public function removeVisita(Visita $visita): static
    {
        if ($this->visitas->removeElement($visita)) {
            // set the owning side to null (unless already changed)
            if ($visita->getLocalidad() === $this) {
                $visita->setLocalidad(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nombre ?? 'N/A';
    }
}
