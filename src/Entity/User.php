<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Validator as AppAssert;

#[AppAssert\EmailCoincideConPersona]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Rol::class, inversedBy: 'usuarios')]
    #[ORM\JoinTable(name: 'user_rol')]
    private Collection $roles;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Persona $Persona = null;


    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /** @deprecated since Symfony 5.3, use getUserIdentifier instead */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // $this->plainPassword = null;
    }

    public function getRoles(): array
    {
        $roles = $this->roles->map(fn(Rol $rol) => $rol->getNombre())->toArray();
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function addRol(Rol $rol): static
    {
        if (!$this->roles->contains($rol)) {
            $this->roles[] = $rol;
        }
        return $this;
    }

    public function removeRol(Rol $rol): static
    {
        $this->roles->removeElement($rol);
        return $this;
    }

    public function getRolesCollection(): Collection
    {
        return $this->roles;
    }

    public function setRolesCollection(Collection $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function __toString(): string
    {
        return $this->email ?? '';
    }

    public function getPersona(): ?Persona
    {
        return $this->Persona;
    }

    public function setPersona(?Persona $Persona): static
    {
        $this->Persona = $Persona;

        return $this;
    }

  
}
