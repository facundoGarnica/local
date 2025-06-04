<?php

namespace App\Entity;

use App\Repository\RolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RolRepository::class)]
class Rol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $nombre = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'roles')]
    private Collection $usuarios;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
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

    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(User $usuario): static
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios[] = $usuario;
            $usuario->addRol($this);
        }

        return $this;
    }

    public function removeUsuario(User $usuario): static
    {
        if ($this->usuarios->removeElement($usuario)) {
            $usuario->removeRol($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nombre ?? '';
    }
}
