<?php

namespace App\Entity;

use App\Repository\PersonaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonaRepository::class)]
class Persona
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column(length: 50)]
    private ?string $apellido = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha_nacimiento = null;

    #[ORM\Column(length: 20)]
    private ?string $dni_pasaporte = null;

    #[ORM\Column(length: 10)]
    private ?string $genero = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $telefono = null;

    #[ORM\Column(length: 50)]
    private ?string $partido = null;

    #[ORM\Column(length: 100)]
    private ?string $calle = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $numero = null;

    #[ORM\Column(nullable: true)]
    private ?int $piso = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $departamento = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $pasillo = null;

    #[ORM\ManyToOne(inversedBy: 'persona')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pais $pais = null;

    #[ORM\ManyToOne(inversedBy: 'persona')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Localidad $localidad = null;

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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fecha_nacimiento;
    }

    public function setFechaNacimiento(\DateTimeInterface $fecha_nacimiento): static
    {
        $this->fecha_nacimiento = $fecha_nacimiento;

        return $this;
    }

    public function getDniPasaporte(): ?string
    {
        return $this->dni_pasaporte;
    }

    public function setDniPasaporte(string $dni_pasaporte): static
    {
        $this->dni_pasaporte = $dni_pasaporte;

        return $this;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(string $genero): static
    {
        $this->genero = $genero;

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

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }


    public function getPartido(): ?string
    {
        return $this->partido;
    }

    public function setPartido(string $partido): static
    {
        $this->partido = $partido;

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

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getPiso(): ?int
    {
        return $this->piso;
    }

    public function setPiso(?int $piso): static
    {
        $this->piso = $piso;

        return $this;
    }

    public function getDepartamento(): ?string
    {
        return $this->departamento;
    }

    public function setDepartamento(?string $departamento): static
    {
        $this->departamento = $departamento;

        return $this;
    }

    public function getPasillo(): ?string
    {
        return $this->pasillo;
    }

    public function setPasillo(?string $pasillo): static
    {
        $this->pasillo = $pasillo;

        return $this;
    }

    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    public function setPais(?Pais $pais): static
    {
        $this->pais = $pais;

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


    public function __toString(): string
    {
        if ($this->getApellido() && $this->getNombre() && $this->getDniPasaporte()) {
            return sprintf('%s, %s  -->DNI: %s', $this->getApellido(), $this->getNombre(), $this->getDniPasaporte());
        } else {
            return 'Sin información de persona';
        }
    }

}
