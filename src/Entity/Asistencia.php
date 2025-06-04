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

  
    #[ORM\Column(length: 20)]
    private ?string $asistencia = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $observacion = null;

    #[ORM\ManyToOne(targetEntity: Cursada::class, inversedBy: 'asistencias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cursada $cursada = null;

    #[ORM\ManyToOne(inversedBy: 'asistencias')]
    private ?CalendarioClase $CalendarioClase = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCalendarioClase(): ?CalendarioClase
    {
        return $this->CalendarioClase;
    }

    public function setCalendarioClase(?CalendarioClase $CalendarioClase): static
    {
        $this->CalendarioClase = $CalendarioClase;

        return $this;
    }
}
