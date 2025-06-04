<?php

namespace App\Entity;

use App\Repository\ExamenFinalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamenFinalRepository::class)]
class ExamenFinal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Docente $presidente_id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Docente $Vocal1_id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Docente $Vocal2_id = null;

    #[ORM\OneToMany(mappedBy: 'examenFinal_id', targetEntity: ExamenAlumno::class)]
    private Collection $examenAlumnos;

    #[ORM\ManyToOne(inversedBy: 'examenFinals')]
    private ?Asignatura $asignatura_id = null;

    public function __construct()
    {
        $this->examenAlumnos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }


    public function getPresidenteId(): ?Docente
    {
        return $this->presidente_id;
    }

    public function setPresidenteId(Docente $presidente_id): static
    {
        $this->presidente_id = $presidente_id;

        return $this;
    }

    public function getVocal1Id(): ?Docente
    {
        return $this->Vocal1_id;
    }

    public function setVocal1Id(?Docente $Vocal1_id): static
    {
        $this->Vocal1_id = $Vocal1_id;

        return $this;
    }

    public function getVocal2Id(): ?Docente
    {
        return $this->Vocal2_id;
    }

    public function setVocal2Id(?Docente $Vocal2_id): static
    {
        $this->Vocal2_id = $Vocal2_id;

        return $this;
    }

    public function getAsignaturaId(): ?Asignatura
    {
        return $this->asignatura_id;
    }

    public function setAsignaturaId(?Asignatura $asignatura_id): static
    {
        $this->asignatura_id = $asignatura_id;

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
            $examenAlumno->setExamenFinalId($this);
        }

        return $this;
    }

    public function removeExamenAlumno(ExamenAlumno $examenAlumno): static
    {
        if ($this->examenAlumnos->removeElement($examenAlumno)) {
            // set the owning side to null (unless already changed)
            if ($examenAlumno->getExamenFinalId() === $this) {
                $examenAlumno->setExamenFinalId(null);
            }
        }

        return $this;
    }


    public function __toString(): string
    {
        $asignatura = $this->getAsignaturaId();
        $tecnicatura = $asignatura ? $asignatura->getTecnicatura() : 'Sin Tecnicatura';
        $asignaturaNombre = $asignatura ? $asignatura->getNombre() : 'Sin Asignatura';
        
        $presidente = $this->getPresidenteId();
        $vocal1 = $this->getVocal1Id();
        $vocal2 = $this->getVocal2Id();
    
        $presidenteNombre = $presidente ? $presidente->getPersona() : 'Sin Presidente';
        $vocal1Nombre = $vocal1 ? $vocal1->getPersona() : 'Sin Vocal 1';
        $vocal2Nombre = $vocal2 ? $vocal2->getPersona() : 'Sin Vocal 2';
    
        return sprintf('%s - %s | Presidente: %s | Vocal 1: %s | Vocal 2: %s', 
                       $asignaturaNombre, 
                       $tecnicatura, 
                       $presidenteNombre, 
                       $vocal1Nombre, 
                       $vocal2Nombre);
    }

}
