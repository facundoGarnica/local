<?php

namespace App\Entity;

use App\Repository\InstitutoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstitutoRepository::class)]
class Instituto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column(length: 50)]
    private ?string $numero = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $url_instituto = null;

    #[ORM\Column(length: 6)]
    private ?string $tipo = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    private ?string $calle = null;

    #[ORM\Column(length: 10)]
    private ?string $altura = null;

    #[ORM\Column(length: 15)]
    private ?string $numero_cue = null;

    #[ORM\ManyToOne(inversedBy: 'instituto')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Localidad $localidad = null;

    #[ORM\OneToMany(mappedBy: 'instituto', targetEntity: Telefono::class)]
    private Collection $telefono;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $instituto = null;

    public function __construct()
    {
        $this->telefono = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

 

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getUrlInstituto(): ?string
    {
        return $this->url_instituto;
    }

    public function setUrlInstituto(?string $url_instituto): static
    {
        $this->url_instituto = $url_instituto;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCalle(): ?string
    {
        return $this->calle;
    }

    public function setCalle(string $calle): static
    {
        $this->calle = $calle;

        return $this;
    }

    public function getAltura(): ?string
    {
        return $this->altura;
    }

    public function setAltura(string $altura): static
    {
        $this->altura = $altura;

        return $this;
    }

    public function getNumeroCue(): ?string
    {
        return $this->numero_cue;
    }

    public function setNumeroCue(string $numero_cue): static
    {
        $this->numero_cue = $numero_cue;

        return $this;
    }

    public function getLocalidad(): ?Localidad
    {
        return $this->localidad;
    }

    public function setLocalidad(?Localidad $localidad): static
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * @return Collection<int, Telefono>
     */
    public function getTelefono(): Collection
    {
        return $this->telefono;
    }

    public function addTelefono(Telefono $telefono): static
    {
        if (!$this->telefono->contains($telefono)) {
            $this->telefono->add($telefono);
            $telefono->setInstituto($this);
        }

        return $this;
    }

    public function removeTelefono(Telefono $telefono): static
    {
        if ($this->telefono->removeElement($telefono)) {
            // set the owning side to null (unless already changed)
            if ($telefono->getInstituto() === $this) {
                $telefono->setInstituto(null);
            }
        }

        return $this;
    }

    public function getInstituto(): ?string
    {
        return $this->instituto;
    }

    public function setInstituto(?string $instituto): static
    {
        $this->instituto = $instituto;

        return $this;
    }
    public function __toString(): string
    {
        return $this->numero ;
    }
}
