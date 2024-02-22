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
    private ?Ruta $codRuta = null;

    #[ORM\OneToMany(mappedBy: 'codTour', targetEntity: Informe::class, orphanRemoval: true)]
    private Collection $informes;

    #[ORM\OneToMany(mappedBy: 'codTour', targetEntity: Reserva::class)]
    private Collection $reservas;

    #[ORM\ManyToOne(inversedBy: 'tours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $guia = null;

    #[ORM\Column]
    private ?bool $finalizado = null;

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

    public function getCodRuta(): ?Ruta
    {
        return $this->codRuta;
    }

    public function setCodRuta(?Ruta $codRuta): static
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

    public function getGuia(): ?User
    {
        return $this->guia;
    }

    public function setGuia(?User $guia): static
    {
        $this->guia = $guia;

        return $this;
    }

    public function isFinalizado(): ?bool
    {
        return $this->finalizado;
    }

    public function setFinalizado(bool $finalizado): static
    {
        $this->finalizado = $finalizado;

        return $this;
    }

}
