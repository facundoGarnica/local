<?php

namespace App\Entity;

use App\Repository\TituloRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TituloRepository::class)]
class Titulo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $descripcion = null;


    #[ORM\OneToMany(mappedBy: 'titulo', targetEntity: Habilitante::class)]
    private Collection $habilitante;

    #[ORM\Column(length: 25)]
    private ?string $institucion = null;

    public function __construct()
    {
        $this->habilitante = new ArrayCollection();
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
     * @return Collection<int, Habilitante>
     */
    public function getHabilitante(): Collection
    {
        return $this->habilitante;
    }

    public function addHabilitante(Habilitante $habilitante): static
    {
        if (!$this->habilitante->contains($habilitante)) {
            $this->habilitante->add($habilitante);
            $habilitante->setTitulo($this);
        }

        return $this;
    }

    public function removeHabilitante(Habilitante $habilitante): static
    {
        if ($this->habilitante->removeElement($habilitante)) {
            // set the owning side to null (unless already changed)
            if ($habilitante->getTitulo() === $this) {
                $habilitante->setTitulo(null);
            }
        }

        return $this;
    }

    public function getInstitucion(): ?string
    {
        return $this->institucion;
    }

    public function setInstitucion(string $institucion): static
    {
        $this->institucion = $institucion;

        return $this;
    }
    public function __toString(): string
    {
        return $this->descripcion ;
    }
}
