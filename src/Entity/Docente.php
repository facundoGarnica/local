<?php

namespace App\Entity;

use App\Repository\DocenteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocenteRepository::class)]
class Docente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $persona = null;

    #[ORM\OneToMany(mappedBy: 'docente', targetEntity: Habilitante::class)]
    private Collection $habilitante;

    #[ORM\OneToMany(mappedBy: 'docente', targetEntity: CursadaDocente::class)]
    private Collection $cursada_docente;

    #[ORM\Column(length: 15)]
    private ?string $foja = null;

    public function __construct()
    {
        $this->habilitante = new ArrayCollection();
        $this->cursada_docente = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(Persona $persona): static
    {
        $this->persona = $persona;

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
            $habilitante->setDocente($this);
        }

        return $this;
    }

    public function removeHabilitante(Habilitante $habilitante): static
    {
        if ($this->habilitante->removeElement($habilitante)) {
            // set the owning side to null (unless already changed)
            if ($habilitante->getDocente() === $this) {
                $habilitante->setDocente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CursadaDocente>
     */
    public function getCursadaDocente(): Collection
    {
        return $this->cursada_docente;
    }

    public function addCursadaDocente(CursadaDocente $cursadaDocente): static
    {
        if (!$this->cursada_docente->contains($cursadaDocente)) {
            $this->cursada_docente->add($cursadaDocente);
            $cursadaDocente->setDocente($this);
        }

        return $this;
    }

    public function removeCursadaDocente(CursadaDocente $cursadaDocente): static
    {
        if ($this->cursada_docente->removeElement($cursadaDocente)) {
            // set the owning side to null (unless already changed)
            if ($cursadaDocente->getDocente() === $this) {
                $cursadaDocente->setDocente(null);
            }
        }

        return $this;
    }

    public function getFoja(): ?string
    {
        return $this->foja;
    }

    public function setFoja(string $foja): static
    {
        $this->foja = $foja;

        return $this;
    }


    public function __toString(): string
    {
        if ($this->persona) {
            return sprintf('%s %s (%s)', $this->persona->getApellido(), $this->persona->getNombre(), $this->persona->getDniPasaporte());
        } else {
            return 'Sin información de persona';
        }
    }
}
