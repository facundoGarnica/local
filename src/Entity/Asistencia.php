<?php

namespace App\Entity;

use App\Repository\AsistenciaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AsistenciaRepository::class)]
class Asistencia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(length: 20)]
    private ?string $asistencia = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $observacion = null;

    #[ORM\ManyToOne(targetEntity: Cursada::class, inversedBy: 'asistencias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cursada $cursada = null;

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

    public function getAsistencia(): ?string
    {
        return $this->asistencia;
    }

    public function setAsistencia(string $asistencia): static
    {
        $this->asistencia = $asistencia;

        return $this;
    }

    public function getObservacion(): ?string
    {
        return $this->observacion;
    }

    public function setObservacion(?string $observacion): static
    {
        $this->observacion = $observacion;

        return $this;
    }

    public function getCursada(): ?Cursada
    {
        return $this->cursada;
    }

    public function setCursada(?Cursada $cursada): static
    {
        $this->cursada = $cursada;

        return $this;
    }
}
