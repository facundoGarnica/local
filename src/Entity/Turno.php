<?php

namespace App\Entity;

use App\Repository\TurnoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TurnoRepository::class)]
class Turno
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16)]
    private ?string $descripcion = null;

    #[ORM\OneToMany(mappedBy: 'turno', targetEntity: Comision::class)]
    private Collection $comision;

    public function __construct()
    {
        $this->comision = new ArrayCollection();
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
     * @return Collection<int, Comision>
     */
    public function getComision(): Collection
    {
        return $this->comision;
    }

    public function addComision(Comision $comision): static
    {
        if (!$this->comision->contains($comision)) {
            $this->comision->add($comision);
            $comision->setTurno($this);
        }

        return $this;
    }

    public function removeComision(Comision $comision): static
    {
        if ($this->comision->removeElement($comision)) {
            // set the owning side to null (unless already changed)
            if ($comision->getTurno() === $this) {
                $comision->setTurno(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->descripcion ;
    }
}
