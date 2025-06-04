<?php

namespace App\Entity;

use App\Repository\AsignaturaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AsignaturaRepository::class)]
class Asignatura
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $anio = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $programa = null;

    #[ORM\Column(nullable: true)]
    private ?int $cant_mod = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $duracion = null;

    #[ORM\ManyToOne(inversedBy: 'asignatura')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tecnicatura $tecnicatura = null;

    #[ORM\OneToMany(mappedBy: 'asignatura', targetEntity: Correlativa::class, orphanRemoval: true)]
    private Collection $correlativas;

    #[ORM\OneToMany(mappedBy: 'asignatura_id', targetEntity: InscripcionFinal::class)]
    private Collection $inscripcionFinals;

    #[ORM\OneToMany(mappedBy: 'asignatura_id', targetEntity: ExamenFinal::class)]
    private Collection $examenFinals;

    #[ORM\OneToMany(mappedBy: 'asignatura', targetEntity: Curso::class)]
    private Collection $cursos;

    

    


    public function __construct()
    {
        $this->correlativas = new ArrayCollection();
        $this->inscripcionFinals = new ArrayCollection();
        $this->examenFinals = new ArrayCollection();
        $this->cursos = new ArrayCollection();
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

    public function getAnio(): ?int
    {
        return $this->anio;
    }

    public function setAnio(int $anio): static
    {
        $this->anio = $anio;

        return $this;
    }

    public function getCantMod(): ?int
    {
        return $this->cant_mod;
    }

    public function setCantMod(int $cant_mod): static
    {
        $this->cant_mod = $cant_mod;

        return $this;
    }

    public function getDuracion(): ?string
    {
        return $this->duracion;
    }

    public function setDuracion(string $duracion): static
    {
        $this->duracion = $duracion;

        return $this;
    }
    
    public function getPrograma(): ?string
    {
        return $this->programa;
    }

    public function setPrograma(string $programa): static
    {
        $this->programa = $programa;

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

    /**
     * @return Collection<int, Correlativa>
     */
    public function getCorrelativas(): Collection
    {
        return $this->correlativas;
    }

    public function addCorrelativa(Correlativa $correlativa): static
    {
        if (!$this->correlativas->contains($correlativa)) {
            $this->correlativas->add($correlativa);
            $correlativa->setCorrelativa($this);
        }

        return $this;
    }

    public function removeCorrelativa(Correlativa $correlativa): static
    {
        if ($this->correlativas->removeElement($correlativa)) {
            if ($correlativa->getCorrelativa() === $this) {
                $correlativa->setCorrelativa(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InscripcionFinal>
     */
    public function getInscripcionFinals(): Collection
    {
        return $this->inscripcionFinals;
    }

    public function addInscripcionFinal(InscripcionFinal $inscripcionFinal): static
    {
        if (!$this->inscripcionFinals->contains($inscripcionFinal)) {
            $this->inscripcionFinals->add($inscripcionFinal);
            $inscripcionFinal->setAsignaturaId($this);
        }

        return $this;
    }

    public function removeInscripcionFinal(InscripcionFinal $inscripcionFinal): static
    {
        if ($this->inscripcionFinals->removeElement($inscripcionFinal)) {
            if ($inscripcionFinal->getAsignaturaId() === $this) {
                $inscripcionFinal->setAsignaturaId(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, ExamenFinal>
     */
    public function getExamenFinals(): Collection
    {
        return $this->examenFinals;
    }

    public function addExamenFinal(ExamenFinal $examenFinal): static
    {
        if (!$this->examenFinals->contains($examenFinal)) {
            $this->examenFinals->add($examenFinal);
            $examenFinal->setAsignaturaId($this);
        }

        return $this;
    }

    public function removeExamenFinal(ExamenFinal $examenFinal): static
    {
        if ($this->examenFinals->removeElement($examenFinal)) {
            if ($examenFinal->getAsignaturaId() === $this) {
                $examenFinal->setAsignaturaId(null);
            }
        }

        return $this;
    }



    public function __toString(): string
    {
        return sprintf('%s (%d) %s', $this->nombre, $this->anio, $this->tecnicatura->getId());
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

    

   

  
}
