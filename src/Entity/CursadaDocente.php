<?php

namespace App\Entity;

use App\Repository\CursadaDocenteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CursadaDocenteRepository::class)]
class CursadaDocente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $toma = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $cese = null;

    #[ORM\ManyToOne(inversedBy: 'cursada_docente')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Docente $docente = null;

    #[ORM\ManyToOne(inversedBy: 'cursada_docente')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Revista $revista = null;

    #[ORM\Column(nullable: true)]
    private ?bool $licencia = null;

    #[ORM\ManyToOne(inversedBy: 'cursadaDocentes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Curso $curso = null;
    


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToma(): ?\DateTimeInterface
    {
        return $this->toma;
    }

    public function setToma(\DateTimeInterface $toma): static
    {
        $this->toma = $toma;

        return $this;
    }

    public function getCese(): ?\DateTimeInterface
    {
        return $this->cese;
    }

    public function setCese(?\DateTimeInterface $cese): static
    {
        $this->cese = $cese;

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

    public function getRevista(): ?Revista
    {
        return $this->revista;
    }

    public function setRevista(?Revista $revista): static
    {
        $this->revista = $revista;

        return $this;
    }



    public function isLicencia(): ?bool
    {
        return $this->licencia;
    }

    public function setLicencia(?bool $licencia): static
    {
        $this->licencia = $licencia;

        return $this;
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


    public function __toString(): string
    {
        if ($this->docente && $this->docente->getPersona()) {
            $persona = $this->docente->getPersona();
            return $persona->getApellido() . ' ' . $persona->getNombre() . ' (' . $persona->getDniPasaporte() . ')';
        }
        return '';
    }
}
