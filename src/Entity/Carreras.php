<?php

namespace App\Entity;

use App\Repository\CarrerasRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
 
#[ORM\Entity(repositoryClass: CarrerasRepository::class)]
class Carreras
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estado = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $inicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fin = null;

    #[ORM\ManyToOne(inversedBy: 'carreras')]
    private ?Alumno $estudiante_id = null;

    #[ORM\ManyToOne(inversedBy: 'carreras')]
    private ?Tecnicatura $tecnicatura_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isEstado(): ?bool
    {
        return $this->estado;
    }

    public function setEstado(?bool $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getInicio(): ?\DateTimeInterface
    {
        return $this->inicio;
    }

    public function setInicio(?\DateTimeInterface $inicio): static
    {
        $this->inicio = $inicio;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(?\DateTimeInterface $fin): static
    {
        $this->fin = $fin;

        return $this;
    }

    public function getEstudianteId(): ?Alumno
    {
        return $this->estudiante_id;
    }

    public function setEstudianteId(?Alumno $estudiante_id): static
    {
        $this->estudiante_id = $estudiante_id;

        return $this;
    }

    public function getTecnicaturaId(): ?Tecnicatura
    {
        return $this->tecnicatura_id;
    }

    public function setTecnicaturaId(?Tecnicatura $tecnicatura_id): static
    {
        $this->tecnicatura_id = $tecnicatura_id;

        return $this;
    }
}
