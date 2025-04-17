<?php

namespace App\Entity;

use App\Repository\PaisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaisRepository::class)]
class Pais
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $descripcion = null;

    #[ORM\OneToMany(mappedBy: 'pais', targetEntity: Provincia::class)]
    private Collection $provincia;

    #[ORM\OneToMany(mappedBy: 'pais', targetEntity: Persona::class)]
    private Collection $persona;

    public function __construct()
    {
        $this->provincia = new ArrayCollection();
        $this->persona = new ArrayCollection();
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
     * @return Collection<int, Provincia>
     */
    public function getProvincia(): Collection
    {
        return $this->provincia;
    }

    public function addProvincium(Provincia $provincium): static
    {
        if (!$this->provincia->contains($provincium)) {
            $this->provincia->add($provincium);
            $provincium->setPais($this);
        }

        return $this;
    }

    public function removeProvincium(Provincia $provincium): static
    {
        if ($this->provincia->removeElement($provincium)) {
            // set the owning side to null (unless already changed)
            if ($provincium->getPais() === $this) {
                $provincium->setPais(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Persona>
     */
    public function getPersona(): Collection
    {
        return $this->persona;
    }

    public function addPersona(Persona $persona): static
    {
        if (!$this->persona->contains($persona)) {
            $this->persona->add($persona);
            $persona->setPais($this);
        }

        return $this;
    }

    public function removePersona(Persona $persona): static
    {
        if ($this->persona->removeElement($persona)) {
            // set the owning side to null (unless already changed)
            if ($persona->getPais() === $this) {
                $persona->setPais(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->descripcion ;
    }
}
