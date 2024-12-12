<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, unique: true)]
    private string $username;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private bool $teacher;

    #[ORM\Column(nullable: true)]
    private ?array $roles = null;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username ?? null;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isTeacher(): ?bool
    {
        return $this->teacher ?? null;
    }

    public function setTeacher(bool $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles ?? null;
    }

    public function setRoles(?array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }
}
