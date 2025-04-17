<?php

namespace App\Entity;

use App\Repository\AlumnoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlumnoRepository::class)]
class Alumno
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $titulo_sec = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $escuela_sec = null;

    #[ORM\Column(nullable: true)]
    private ?int $anio_egreso = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $persona = null;

    #[ORM\OneToMany(mappedBy: 'alumno', targetEntity: Cursada::class)]
    private Collection $cursada;

    #[ORM\OneToMany(mappedBy: 'alumno_id', targetEntity: InscripcionFinal::class)]
    private Collection $inscripcionFinals;

    #[ORM\OneToMany(mappedBy: 'alumno_id', targetEntity: ExamenAlumno::class)]
    private Collection $examenAlumnos;

    #[ORM\OneToMany(mappedBy: 'estudiante_id', targetEntity: Carreras::class)]
    private Collection $carreras;

    public function __construct()
    {
        $this->cursada = new ArrayCollection();
        $this->inscripcionFinals = new ArrayCollection();
        $this->examenAlumnos = new ArrayCollection();
        $this->carreras = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTituloSec(): ?string
    {
        return $this->titulo_sec;
    }

    public function setTituloSec(?string $titulo_sec): static
    {
        $this->titulo_sec = $titulo_sec;

        return $this;
    }

    public function getEscuelaSec(): ?string
    {
        return $this->escuela_sec;
    }

    public function setEscuelaSec(?string $escuela_sec): static
    {
        $this->escuela_sec = $escuela_sec;

        return $this;
    }

    public function getAnioEgreso(): ?int
    {
        return $this->anio_egreso;
    }

    public function setAnioEgreso(?int $anio_egreso): static
    {
        $this->anio_egreso = $anio_egreso;

        return $this;
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
     * @return Collection<int, Cursada>
     */
    public function getCursada(): Collection
    {
        return $this->cursada;
    }

    public function addCursada(Cursada $cursada): static
    {
        if (!$this->cursada->contains($cursada)) {
            $this->cursada->add($cursada);
            $cursada->setAlumno($this);
        }

        return $this;
    }

    public function removeCursada(Cursada $cursada): static
    {
        if ($this->cursada->removeElement($cursada)) {
            // set the owning side to null (unless already changed)
            if ($cursada->getAlumno() === $this) {
                $cursada->setAlumno(null);
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
            $inscripcionFinal->setAlumnoId($this);
        }

        return $this;
    }

    public function removeInscripcionFinal(InscripcionFinal $inscripcionFinal): static
    {
        if ($this->inscripcionFinals->removeElement($inscripcionFinal)) {
            // set the owning side to null (unless already changed)
            if ($inscripcionFinal->getAlumnoId() === $this) {
                $inscripcionFinal->setAlumnoId(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, ExamenAlumno>
     */
    public function getExamenAlumnos(): Collection
    {
        return $this->examenAlumnos;
    }

    public function addExamenAlumno(ExamenAlumno $examenAlumno): static
    {
        if (!$this->examenAlumnos->contains($examenAlumno)) {
            $this->examenAlumnos->add($examenAlumno);
            $examenAlumno->setAlumnoId($this);
        }

        return $this;
    }

    public function removeExamenAlumno(ExamenAlumno $examenAlumno): static
    {
        if ($this->examenAlumnos->removeElement($examenAlumno)) {
            // set the owning side to null (unless already changed)
            if ($examenAlumno->getAlumnoId() === $this) {
                $examenAlumno->setAlumnoId(null);
            }
        }

        return $this;
    }
















    public function __toString(): string
    {
        if ($this->persona) {
            return sprintf('%s %s -->DNI: %s', $this->persona->getApellido(), $this->persona->getNombre(), $this->persona->getDniPasaporte());
        } else {
            return 'Sin información de persona';
        }
    }

    /**
     * @return Collection<int, Carreras>
     */
    public function getCarreras(): Collection
    {
        return $this->carreras;
    }

    public function addCarrera(Carreras $carrera): static
    {
        if (!$this->carreras->contains($carrera)) {
            $this->carreras->add($carrera);
            $carrera->setEstudianteId($this);
        }

        return $this;
    }

    public function removeCarrera(Carreras $carrera): static
    {
        if ($this->carreras->removeElement($carrera)) {
            // set the owning side to null (unless already changed)
            if ($carrera->getEstudianteId() === $this) {
                $carrera->setEstudianteId(null);
            }
        }

        return $this;
    }

}
