<?php

namespace App\Entity;

use DateTimeImmutable;
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
    
    #[ORM\Column(length: 16, nullable: true)]
    private string $studentClass;

    #[ORM\Column(nullable: true)]
    private ?array $roles = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $lastLoginAt;

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

    public function getStudentClass(): ?string
    {
        return $this->studentClass ?? null;
    }

    public function setStudentClass(?string $studentClass): static
    {
        $this->studentClass = $studentClass;

        return $this;
    }

    public function isStudent(): bool
    {
        return $this->studentClass !== null;
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

    public function getLastLoginAt(): ?DateTimeImmutable
    {
        return $this->lastLoginAt ?? null;
    }

    public function setLastLoginAt(?DateTimeImmutable $lastLoginAt): self
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    public function getFundamentalRoles(): array
    {
        return [$this->getFundamentalRole()];
    }

    private function getFundamentalRole(): string
    {
        $testRoles = ['ROLE_SUPERADMIN', 'ROLE_ADMIN', 'ROLE_TEACHER'];
        if ($this->roles !== null) {
            foreach ($testRoles as $role) {
                if (in_array($role, $this->roles)) {
                    return $role;
                }
            }
        }
        if ($this->roles === null || in_array('ROLE_DEFAULT', $this->roles)) {
            if ($this->isTeacher()) {
                return 'ROLE_TEACHER';
            }
            if ($this->isStudent()) {
                return 'ROLE_STUDENT';
            }
        }
        return 'ROLE_OTHER';
    }
}
