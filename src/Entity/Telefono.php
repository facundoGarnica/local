<?php

namespace App\Entity;

use App\Repository\TelefonoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TelefonoRepository::class)]
class Telefono
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $numero = null;

    #[ORM\ManyToOne(inversedBy: 'telefono')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Instituto $instituto = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getInstituto(): ?Instituto
    {
        return $this->instituto;
    }

    public function setInstituto(?Instituto $instituto): static
    {
        $this->instituto = $instituto;

        return $this;
    }
}
