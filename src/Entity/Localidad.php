<?php

namespace App\Entity;

use App\Repository\LocalidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocalidadRepository::class)]
class Localidad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $codigo_postal = null;

    #[ORM\ManyToOne(inversedBy: 'localidad')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provincia $provincia = null;


    #[ORM\OneToMany(mappedBy: 'localidad', targetEntity: Persona::class)]
    private Collection $persona;

    #[ORM\OneToMany(mappedBy: 'localidad', targetEntity: Instituto::class)]
    private Collection $instituto;

    #[ORM\ManyToOne(inversedBy: 'localidad')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $region = null;

    public function __construct()
    {
        $this->persona = new ArrayCollection();
        $this->instituto = new ArrayCollection();
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

    public function getCodigoPostal(): ?string
    {
        return $this->codigo_postal;
    }

    public function setCodigoPostal(?string $codigo_postal): static
    {
        $this->codigo_postal = $codigo_postal;

        return $this;
    }

    public function getProvincia(): ?Provincia
    {
        return $this->provincia;
    }

    public function setProvincia(?Provincia $provincia): static
    {
        $this->provincia = $provincia;

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
            $persona->setLocalidad($this);
        }

        return $this;
    }

    public function removePersona(Persona $persona): static
    {
        if ($this->persona->removeElement($persona)) {
            // set the owning side to null (unless already changed)
            if ($persona->getLocalidad() === $this) {
                $persona->setLocalidad(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Instituto>
     */
    public function getInstituto(): Collection
    {
        return $this->instituto;
    }

    public function addInstituto(Instituto $instituto): static
    {
        if (!$this->instituto->contains($instituto)) {
            $this->instituto->add($instituto);
            $instituto->setLocalidad($this);
        }

        return $this;
    }

    public function removeInstituto(Instituto $instituto): static
    {
        if ($this->instituto->removeElement($instituto)) {
            // set the owning side to null (unless already changed)
            if ($instituto->getLocalidad() === $this) {
                $instituto->setLocalidad(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }
    public function __toString(): string
    {
        return $this->nombre ;
    }
}
