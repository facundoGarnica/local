<?php

namespace App\Entity;

use App\Repository\ExamenAlumnoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamenAlumnoRepository::class)]
class ExamenAlumno
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $nota = null;

    #[ORM\Column(length: 15)]
    private ?string $tomo = null;

    #[ORM\Column]
    private ?int $folio = null;

    #[ORM\ManyToOne(inversedBy: 'examenAlumnos')]
    private ?Alumno $alumno_id = null;

    #[ORM\ManyToOne(inversedBy: 'examenAlumnos')]
    private ?ExamenFinal $examenFinal_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNota(): ?string
    {
        return $this->nota;
    }

    public function setNota(string $nota): static
    {
        $this->nota = $nota;

        return $this;
    }

    public function getTomo(): ?string
    {
        return $this->tomo;
    }

    public function setTomo(string $tomo): static
    {
        $this->tomo = $tomo;

        return $this;
    }

    public function getFolio(): ?int
    {
        return $this->folio;
    }

    public function setFolio(int $folio): static
    {
        $this->folio = $folio;

        return $this;
    }

    public function getAlumnoId(): ?Alumno
    {
        return $this->alumno_id;
    }

    public function setAlumnoId(?Alumno $alumno_id): static
    {
        $this->alumno_id = $alumno_id;

        return $this;
    }

    public function getExamenFinalId(): ?ExamenFinal
    {
        return $this->examenFinal_id;
    }

    public function setExamenFinalId(?ExamenFinal $examenFinal_id): static
    {
        $this->examenFinal_id = $examenFinal_id;

        return $this;
    }
}
