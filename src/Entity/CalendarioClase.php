<?php

namespace App\Entity;

use App\Repository\CalendarioClaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendarioClaseRepository::class)]
class CalendarioClase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Fecha = null;

    #[ORM\ManyToOne(inversedBy: 'calendarioClases')]
    private ?Modalidad $Modalidad = null;

    #[ORM\ManyToOne(inversedBy: 'calendarioClases')]
    private ?Curso $Curso = null;

    #[ORM\Column(length: 255)]
    private ?string $Observacion = null;

    #[ORM\OneToMany(mappedBy: 'CalendarioClase', targetEntity: Asistencia::class)]
    private Collection $asistencias;

    public function __construct()
    {
        $this->asistencias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->Fecha;
    }

    public function setFecha(\DateTimeInterface $Fecha): static
    {
        $this->Fecha = $Fecha;

        return $this;
    }

    public function getModalidad(): ?Modalidad
    {
        return $this->Modalidad;
    }

    public function setModalidad(?Modalidad $Modalidad): static
    {
        $this->Modalidad = $Modalidad;

        return $this;
    }

    public function getCurso(): ?Curso
    {
        return $this->Curso;
    }

    public function setCurso(?Curso $Curso): static
    {
        $this->Curso = $Curso;

        return $this;
    }

    public function getObservacion(): ?string
    {
        return $this->Observacion;
    }

    public function setObservacion(string $Observacion): static
    {
        $this->Observacion = $Observacion;

        return $this;
    }

    /**
     * @return Collection<int, Asistencia>
     */
    public function getAsistencias(): Collection
    {
        return $this->asistencias;
    }

    public function addAsistencia(Asistencia $asistencia): static
    {
        if (!$this->asistencias->contains($asistencia)) {
            $this->asistencias->add($asistencia);
            $asistencia->setCalendarioClase($this);
        }

        return $this;
    }

    public function removeAsistencia(Asistencia $asistencia): static
    {
        if ($this->asistencias->removeElement($asistencia)) {
            // set the owning side to null (unless already changed)
            if ($asistencia->getCalendarioClase() === $this) {
                $asistencia->setCalendarioClase(null);
            }
        }

        return $this;
    }
}
