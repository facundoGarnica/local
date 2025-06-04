<?php

namespace App\Entity;

use App\Repository\HorarioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HorarioRepository::class)]
class Horario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $Dia = null;

    #[ORM\Column(length: 40)]
    private ?string $Horario_inicio = null;

    #[ORM\Column(length: 40)]
    private ?string $Horario_fin = null;

    #[ORM\Column(length: 255)]
    private ?string $Cant_modulos = null;

    #[ORM\ManyToOne(inversedBy: 'horarios')]
    private ?Curso $Curso = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDia(): ?string
    {
        return $this->Dia;
    }

    public function setDia(string $Dia): static
    {
        $this->Dia = $Dia;

        return $this;
    }

    public function getHorarioInicio(): ?string
    {
        return $this->Horario_inicio;
    }

    public function setHorarioInicio(string $Horario_inicio): static
    {
        $this->Horario_inicio = $Horario_inicio;

        return $this;
    }

    public function getHorarioFin(): ?string
    {
        return $this->Horario_fin;
    }

    public function setHorarioFin(string $Horario_fin): static
    {
        $this->Horario_fin = $Horario_fin;

        return $this;
    }

    public function getCantModulos(): ?string
    {
        return $this->Cant_modulos;
    }

    public function setCantModulos(string $Cant_modulos): static
    {
        $this->Cant_modulos = $Cant_modulos;

        return $this;
    }

    public function getCurso(): ?Asignatura
    {
        return $this->Curso;
    }

    public function setCurso(?Curso $Curso): static
    {
        $this->Curso = $Curso;

        return $this;
    }
}
