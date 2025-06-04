<?php

namespace App\Entity;

use App\Repository\HabilitanteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabilitanteRepository::class)]
class Habilitante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $anio_egreso = null;

    #[ORM\Column]
    private ?float $promedio = null;

    #[ORM\ManyToOne(inversedBy: 'habilitante')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Titulo $titulo = null;

    #[ORM\ManyToOne(inversedBy: 'habilitante')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Docente $docente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnioEgreso(): ?int
    {
        return $this->anio_egreso;
    }

    public function setAnioEgreso(int $anio_egreso): static
    {
        $this->anio_egreso = $anio_egreso;

        return $this;
    }

    public function getPromedio(): ?float
    {
        return $this->promedio;
    }

    public function setPromedio(float $promedio): static
    {
        $this->promedio = $promedio;

        return $this;
    }

    public function getTitulo(): ?Titulo
    {
        return $this->titulo;
    }

    public function setTitulo(?Titulo $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDocente(): ?Docente
    {
        return $this->docente;
    }

    public function setDocente(?Docente $docente): static
    {
        $this->docente = $docente;

        return $this;
    }
}
