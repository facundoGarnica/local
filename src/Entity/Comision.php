<?php

namespace App\Entity;

use App\Repository\ComisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComisionRepository::class)]
class Comision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $anio = null;

    #[ORM\Column(length: 5)]
    private ?string $comision = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estado = null;

    #[ORM\ManyToOne(inversedBy: 'comision')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Turno $turno = null;

    #[ORM\ManyToOne(inversedBy: 'comision')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tecnicatura $tecnicatura = null;

    #[ORM\OneToMany(mappedBy: 'comision', targetEntity: Curso::class)]
    private Collection $cursos;

    #[ORM\Column(length: 4)]
    private ?string $ciclo_lectivo = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnio(): ?int
    {
        return $this->anio;
    }

    public function setAnio(int $anio): static
    {
        $this->anio = $anio;

        return $this;
    }

    public function getComision(): ?string
    {
        return $this->comision;
    }

    public function setComision(string $comision): static
    {
        $this->comision = $comision;

        return $this;
    }

    public function isEstado(): ?bool
    {
        return $this->estado;
    }

    public function setEstado(?bool $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getTurno(): ?Turno
    {
        return $this->turno;
    }

    public function setTurno(?Turno $turno): static
    {
        $this->turno = $turno;

        return $this;
    }

    public function getTecnicatura(): ?Tecnicatura
    {
        return $this->tecnicatura;
    }

    public function setTecnicatura(?Tecnicatura $tecnicatura): static
    {
        $this->tecnicatura = $tecnicatura;

        return $this;
    }


    public function __toString(): string
    {
        return sprintf('%s (%d)', $this->comision, $this->anio);
    }

    /**
     * @return Collection<int, Curso>
     */
    public function getCursos(): Collection
    {
        return $this->cursos;
    }

    public function addCurso(Curso $curso): self
    {
        if (!$this->cursos->contains($curso)) {
            $this->cursos->add($curso);
            $curso->setAsignatura($this);
        }

        return $this;
    }

    public function removeCurso(Curso $curso): self
    {
        if ($this->cursos->removeElement($curso)) {
            // set the owning side to null (unless already changed)
            if ($curso->getAsignatura() === $this) {
                $curso->setAsignatura(null);
            }
        }

        return $this;
    }

    public function getCicloLectivo(): ?string
    {
        return $this->ciclo_lectivo;
    }

    public function setCicloLectivo(string $ciclo_lectivo): static
    {
        $this->ciclo_lectivo = $ciclo_lectivo;

        return $this;
    }
    
}
