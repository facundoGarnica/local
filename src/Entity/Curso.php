<?php

namespace App\Entity;

use App\Repository\CursoRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CursoRepository::class)]
class Curso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Asignatura::class, inversedBy: 'cursos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Asignatura $asignatura = null;

    #[ORM\ManyToOne(targetEntity: Comision::class, inversedBy: 'cursos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comision $comision = null;

    #[ORM\OneToMany(mappedBy: 'curso', targetEntity: Cursada::class)]
    private Collection $cursadas;

    #[ORM\OneToMany(mappedBy: 'curso', targetEntity: CursadaDocente::class)]
    private Collection $cursadaDocentes;

    #[ORM\Column(length: 30)]
    private ?string $CUPOF = null;
    #[ORM\OneToMany(mappedBy: 'Asignatura', targetEntity: Horario::class)]
    private Collection $horarios;

    #[ORM\OneToMany(mappedBy: 'Curso', targetEntity: CalendarioClase::class)]
    private Collection $calendarioClases;


    public function __construct()
    {
        $this->cursadas = new ArrayCollection();
        $this->cursadaDocentes = new ArrayCollection();
        $this->calendarioClases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAsignatura(): ?Asignatura
    {
        return $this->asignatura;
    }

    public function setAsignatura(?Asignatura $asignatura): self
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    public function getComision(): ?Comision
    {
        return $this->comision;
    }

    public function setComision(?Comision $comision): self
    {
        $this->comision = $comision;

        return $this;
    }

    /**
     * @return Collection<int, Cursada>
     */
    public function getCursadas(): Collection
    {
        return $this->cursadas;
    }

    public function addCursada(Cursada $cursada): static
    {
        if (!$this->cursadas->contains($cursada)) {
            $this->cursadas->add($cursada);
            $cursada->setCurso($this);
        }

        return $this;
    }

    public function removeCursada(Cursada $cursada): static
    {
        if ($this->cursadas->removeElement($cursada)) {
            if ($cursada->getCurso() === $this) {
                $cursada->setCurso(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CursadaDocente>
     */
    public function getCursadaDocentes(): Collection
    {
        return $this->cursadaDocentes;
    }

    public function addCursadaDocente(CursadaDocente $cursadaDocente): static
    {
        if (!$this->cursadaDocentes->contains($cursadaDocente)) {
            $this->cursadaDocentes->add($cursadaDocente);
            $cursadaDocente->setCurso($this);
        }

        return $this;
    }

    public function removeCursadaDocente(CursadaDocente $cursadaDocente): static
    {
        if ($this->cursadaDocentes->removeElement($cursadaDocente)) {
            if ($cursadaDocente->getCurso() === $this) {
                $cursadaDocente->setCurso(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->getAsignatura();
    }
    public function getCUPOF(): ?string
    {
        return $this->CUPOF;
    }

    public function setCUPOF(string $CUPOF): static
    {
        $this->CUPOF = $CUPOF;

        return $this;
    }
    
 /**
     * @return Collection<int, Horario>
     */
    public function getHorarios(): Collection
    {
        return $this->horarios;
    }

    public function addHorario(Horario $horario): static
    {
        if (!$this->horarios->contains($horario)) {
            $this->horarios->add($horario);
            $horario->setAsignatura($this);
        }

        return $this;
    }
      public function removeHorario(Horario $horario): static
    {
        if ($this->horarios->removeElement($horario)) {
            // set the owning side to null (unless already changed)
            if ($horario->getAsignatura() === $this) {
                $horario->setAsignatura(null);
            }
        }

        return $this;
    }

      /**
       * @return Collection<int, CalendarioClase>
       */
      public function getCalendarioClases(): Collection
      {
          return $this->calendarioClases;
      }

      public function addCalendarioClase(CalendarioClase $calendarioClase): static
      {
          if (!$this->calendarioClases->contains($calendarioClase)) {
              $this->calendarioClases->add($calendarioClase);
              $calendarioClase->setCurso($this);
          }

          return $this;
      }

      public function removeCalendarioClase(CalendarioClase $calendarioClase): static
      {
          if ($this->calendarioClases->removeElement($calendarioClase)) {
              // set the owning side to null (unless already changed)
              if ($calendarioClase->getCurso() === $this) {
                  $calendarioClase->setCurso(null);
              }
          }

          return $this;
      }
    
}
