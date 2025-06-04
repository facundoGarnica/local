<?php

namespace App\Entity;

use App\Repository\ModalidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModalidadRepository::class)]
class Modalidad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $descripcion = null;

    #[ORM\OneToMany(mappedBy: 'modalidad', targetEntity: Cursada::class)]
    private Collection $cursada;

    #[ORM\OneToMany(mappedBy: 'Modalidad', targetEntity: CalendarioClase::class)]
    private Collection $calendarioClases;

    public function __construct()
    {
        $this->cursada = new ArrayCollection();
        $this->calendarioClases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Cursada>
     */
    public function getCursada(): Collection
    {
        return $this->cursada;
    }

    public function addCursada(Cursada $cursada): static
    {
        if (!$this->cursada->contains($cursada)) {
            $this->cursada->add($cursada);
            $cursada->setModalidad($this);
        }

        return $this;
    }

    public function removeCursada(Cursada $cursada): static
    {
        if ($this->cursada->removeElement($cursada)) {
            // set the owning side to null (unless already changed)
            if ($cursada->getModalidad() === $this) {
                $cursada->setModalidad(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->descripcion ;
    }

    /**
     * @return Collection<int, CalendarioClase>
     */
    public function getCalendarioClases(): Collection
    {
        return $this->calendarioClases;
    }

    public function addCalendarioClase(CalendarioClase $calendarioClase): static
    {
        if (!$this->calendarioClases->contains($calendarioClase)) {
            $this->calendarioClases->add($calendarioClase);
            $calendarioClase->setModalidad($this);
        }

        return $this;
    }

    public function removeCalendarioClase(CalendarioClase $calendarioClase): static
    {
        if ($this->calendarioClases->removeElement($calendarioClase)) {
            // set the owning side to null (unless already changed)
            if ($calendarioClase->getModalidad() === $this) {
                $calendarioClase->setModalidad(null);
            }
        }

        return $this;
    }
}
