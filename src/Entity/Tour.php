<?php

namespace App\Entity;

use App\Repository\TourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TourRepository::class)]
class Tour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaHora = null;

    #[ORM\ManyToOne(inversedBy: 'tours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ruta $codRuta = null;

    #[ORM\OneToMany(mappedBy: 'codTour', targetEntity: Informe::class, orphanRemoval: true)]
    private Collection $informes;

    #[ORM\OneToMany(mappedBy: 'codTour', targetEntity: Reserva::class)]
    private Collection $reservas;

    public function __construct()
    {
        $this->informes = new ArrayCollection();
        $this->reservas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaHora(): ?\DateTimeInterface
    {
        return $this->fechaHora;
    }

    public function setFechaHora(\DateTimeInterface $fechaHora): static
    {
        $this->fechaHora = $fechaHora;

        return $this;
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

    /**
     * @return Collection<int, Informe>
     */
    public function getInformes(): Collection
    {
        return $this->informes;
    }

    public function addInforme(Informe $informe): static
    {
        if (!$this->informes->contains($informe)) {
            $this->informes->add($informe);
            $informe->setCodTour($this);
        }

        return $this;
    }

    public function removeInforme(Informe $informe): static
    {
        if ($this->informes->removeElement($informe)) {
            // set the owning side to null (unless already changed)
            if ($informe->getCodTour() === $this) {
                $informe->setCodTour(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reserva>
     */
    public function getReservas(): Collection
    {
        return $this->reservas;
    }

    public function addReserva(Reserva $reserva): static
    {
        if (!$this->reservas->contains($reserva)) {
            $this->reservas->add($reserva);
            $reserva->setCodTour($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): static
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getCodTour() === $this) {
                $reserva->setCodTour(null);
            }
        }

        return $this;
    }
}
