<?php

namespace App\Entity;

use App\Repository\ProvinciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProvinciaRepository::class)]
class Provincia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(inversedBy: 'provincia')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pais $pais = null;

    #[ORM\OneToMany(mappedBy: 'provincia', targetEntity: Localidad::class)]
    private Collection $localidad;

    public function __construct()
    {
        $this->localidad = new ArrayCollection();
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

    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    public function setPais(?Pais $pais): static
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * @return Collection<int, Localidad>
     */
    public function getLocalidad(): Collection
    {
        return $this->localidad;
    }

    public function addLocalidad(Localidad $localidad): static
    {
        if (!$this->localidad->contains($localidad)) {
            $this->localidad->add($localidad);
            $localidad->setProvincia($this);
        }

        return $this;
    }

    public function removeLocalidad(Localidad $localidad): static
    {
        if ($this->localidad->removeElement($localidad)) {
            // set the owning side to null (unless already changed)
            if ($localidad->getProvincia() === $this) {
                $localidad->setProvincia(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->descripcion ;
    }
}
