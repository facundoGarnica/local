<?php

namespace App\Entity;

use App\Repository\RevistaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RevistaRepository::class)]
class Revista
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $descripcion = null;

    #[ORM\OneToMany(mappedBy: 'revista', targetEntity: CursadaDocente::class)]
    private Collection $cursada_docente;

    public function __construct()
    {
        $this->cursada_docente = new ArrayCollection();
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
            $cursadaDocente->setRevista($this);
        }

        return $this;
    }

    public function removeCursadaDocente(CursadaDocente $cursadaDocente): static
    {
        if ($this->cursada_docente->removeElement($cursadaDocente)) {
            // set the owning side to null (unless already changed)
            if ($cursadaDocente->getRevista() === $this) {
                $cursadaDocente->setRevista(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->descripcion ;
    }
}
