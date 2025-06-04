<?php

namespace App\Entity;

use App\Repository\NotaRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: NotaRepository::class)]
class Nota
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
 

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $parcial = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $recuperatorio1 = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $parcial2 = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $recuperatorio2 = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParcial(): ?string
    {
        return $this->parcial;
    }

    public function setParcial(?string $parcial): static
    {
        $this->parcial = $parcial;

        return $this;
    }

    public function getRecuperatorio1(): ?string
    {
        return $this->recuperatorio1;
    }

    public function setRecuperatorio1(?string $recuperatorio1): static
    {
        $this->recuperatorio1 = $recuperatorio1;

        return $this;
    }

    public function getParcial2(): ?string
    {
        return $this->parcial2;
    }

    public function setParcial2(?string $parcial2): static
    {
        $this->parcial2 = $parcial2;

        return $this;
    }

    public function getRecuperatorio2(): ?string
    {
        return $this->recuperatorio2;
    }

    public function setRecuperatorio2(?string $recuperatorio2): static
    {
        $this->recuperatorio2 = $recuperatorio2;

        return $this;
    }


    public function __toString(): string
    {
        return ' ( P: ' . $this->parcial . ' R1: ' . $this->recuperatorio1 . ' P2: ' . $this->parcial2 . ' R2: ' . $this->recuperatorio2 . ' )';
    }
}
