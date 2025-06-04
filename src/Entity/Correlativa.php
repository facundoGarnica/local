<?php

namespace App\Entity;

use App\Repository\CorrelativaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CorrelativaRepository::class)]
class Correlativa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'correlativas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Asignatura $asignatura = null;

    #[ORM\ManyToOne(targetEntity: Asignatura::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Asignatura $correlativa = null;

    #[ORM\Column(length: 10)]
    private ?string $motivo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAsignatura(): ?Asignatura
    {
        return $this->asignatura;
    }

    public function setAsignatura(Asignatura $asignatura): static
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    public function getCorrelativa(): ?Asignatura
    {
        return $this->correlativa;
    }

    public function setCorrelativa(Asignatura $correlativa): static
    {
        $this->correlativa = $correlativa;

        return $this;
    }

    public function getMotivo(): ?string
    {
        return $this->motivo;
    }

    public function setMotivo(string $motivo): static
    {
        $this->motivo = $motivo;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s', $this->getCorrelativa());
    }
}
