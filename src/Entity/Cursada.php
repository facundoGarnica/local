<?php

namespace App\Entity;

use App\Repository\CursadaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CursadaRepository::class)]
class Cursada
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; 

    #[ORM\ManyToOne(inversedBy: 'cursada')]
    private ?Alumno $alumno = null;

    #[ORM\ManyToOne(inversedBy: 'cursada')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Modalidad $modalidad = null;

    #[ORM\Column(length: 15)]
    private ?string $condicion = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Nota $nota_id = null;

    #[ORM\ManyToOne(inversedBy: 'cursadas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Curso $curso = null;

    #[ORM\OneToMany(mappedBy: 'cursada', targetEntity: Asistencia::class, cascade: ['persist', 'remove'])]
    private Collection $asistencias;

    public function __construct()
    {
        $this->asistencias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlumno(): ?Alumno
    {
        return $this->alumno;
    }

    public function setAlumno(?Alumno $alumno): static
    {
        $this->alumno = $alumno;

        return $this;
    }

    public function getModalidad(): ?Modalidad
    {
        return $this->modalidad;
    }

    public function setModalidad(?Modalidad $modalidad): static
    {
        $this->modalidad = $modalidad;

        return $this;
    }

    public function getCondicion(): ?string
    {
        return $this->condicion;
    }

    public function setCondicion(string $condicion): static
    {
        $this->condicion = $condicion;

        return $this;
    }

    public function getNotaId(): ?Nota
    {
        return $this->nota_id;
    }

    public function setNotaId(?Nota $nota_id): static
    {
        $this->nota_id = $nota_id;

        return $this;
    }


    public function __toString(): string
    {
        if ($this->alumno && $this->alumno->getPersona()) {
            $persona = $this->alumno->getPersona();
            $nombreCompleto = sprintf('%s, %s (%s)', $persona->getApellido(), $persona->getNombre(), $persona->getDniPasaporte());
        } else {
            $nombreCompleto = 'Sin Alumno';
        }
    
        return sprintf('%s  ', 
                       $nombreCompleto, 
                       );
    }


    public function getCurso(): ?Curso
    {
        return $this->curso;
    }

    public function setCurso(?Curso $curso): static
    {
        $this->curso = $curso;

        return $this;
    }


    public function getAsistencias(): Collection
    {
        return $this->asistencias;
    }

    public function addAsistencia(Asistencia $asistencia): static
    {
        if (!$this->asistencias->contains($asistencia)) {
            $this->asistencias->add($asistencia);
            $asistencia->setCursada($this);
        }

        return $this;
    }

    public function removeAsistencia(Asistencia $asistencia): static
    {
        if ($this->asistencias->removeElement($asistencia)) {
            // set the owning side to null (unless already changed)
            if ($asistencia->getCursada() === $this) {
                $asistencia->setCursada(null);
            }
        }

        return $this;
    }
    
}


