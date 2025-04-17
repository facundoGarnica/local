<?php

namespace App\Entity;

use App\Repository\CursoRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CursoRepository::class)]
class Curso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ciclo_lectivo = null;

    #[ORM\Column(length: 15)]
    private ?string $horario = null;

    #[ORM\ManyToOne(targetEntity: Asignatura::class, inversedBy: 'cursos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Asignatura $asignatura = null;

    #[ORM\ManyToOne(targetEntity: Comision::class, inversedBy: 'cursos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comision $comision = null;

    #[ORM\OneToMany(mappedBy: 'curso', targetEntity: Cursada::class)]
    private Collection $cursadas;

    #[ORM\OneToMany(mappedBy: 'curso', targetEntity: CursadaDocente::class)]
    private Collection $cursadaDocentes;

    public function __construct()
    {
        $this->cursadas = new ArrayCollection();
        $this->cursadaDocentes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCicloLectivo(): ?int
    {
        return $this->ciclo_lectivo;
    }

    public function setCicloLectivo(int $ciclo_lectivo): static
    {
        $this->ciclo_lectivo = $ciclo_lectivo;

        return $this;
    }

    public function getHorario(): ?string
    {
        return $this->horario;
    }

    public function setHorario(string $horario): self
    {
        $this->horario = $horario;

        return $this;
    }

    public function getAsignatura(): ?Asignatura
    {
        return $this->asignatura;
    }

    public function setAsignatura(?Asignatura $asignatura): self
    {
        $this->asignatura = $asignatura;

        return $this;
    }

    public function getComision(): ?Comision
    {
        return $this->comision;
    }

    public function setComision(?Comision $comision): self
    {
        $this->comision = $comision;

        return $this;
    }

    /**
     * @return Collection<int, Cursada>
     */
    public function getCursadas(): Collection
    {
        return $this->cursadas;
    }

    public function addCursada(Cursada $cursada): static
    {
        if (!$this->cursadas->contains($cursada)) {
            $this->cursadas->add($cursada);
            $cursada->setCurso($this);
        }

        return $this;
    }

    public function removeCursada(Cursada $cursada): static
    {
        if ($this->cursadas->removeElement($cursada)) {
            // set the owning side to null (unless already changed)
            if ($cursada->getCurso() === $this) {
                $cursada->setCurso(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CursadaDocente>
     */
    public function getCursadaDocentes(): Collection
    {
        return $this->cursadaDocentes;
    }

    public function addCursadaDocente(CursadaDocente $cursadaDocente): static
    {
        if (!$this->cursadaDocentes->contains($cursadaDocente)) {
            $this->cursadaDocentes->add($cursadaDocente);
            $cursadaDocente->setCurso($this);
        }

        return $this;
    }

    public function removeCursadaDocente(CursadaDocente $cursadaDocente): static
    {
        if ($this->cursadaDocentes->removeElement($cursadaDocente)) {
            // set the owning side to null (unless already changed)
            if ($cursadaDocente->getCurso() === $this) {
                $cursadaDocente->setCurso(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (%d) %s', $this->ciclo_lectivo, $this->horario, $this->getAsignatura());
    }

}

