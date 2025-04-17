<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $numero = null;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Localidad::class)]
    private Collection $localidad;

    public function __construct()
    {
        $this->localidad = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

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
            $localidad->setRegion($this);
        }

        return $this;
    }

    public function removeLocalidad(Localidad $localidad): static
    {
        if ($this->localidad->removeElement($localidad)) {
            // set the owning side to null (unless already changed)
            if ($localidad->getRegion() === $this) {
                $localidad->setRegion(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->numero ;
    }
}
