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

    #[ORM\Column(length: 255)]
    private string $originalRole;
    
    #[ORM\Column(length: 16, nullable: true)]
    private ?string $originalStudentClass = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $effectiveRole = null;
    
    #[ORM\Column(length: 16, nullable: true)]
    private ?string $effectiveStudentClass = null;

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

    public function getOriginalRole(): ?string
    {
        return $this->originalRole ?? null;
    }

    public function setOriginalRole(string $originalRole): static
    {
        $this->originalRole = $originalRole;

        return $this;
    }

    public function getOriginalStudentClass(): ?string
    {
        return $this->originalStudentClass ?? null;
    }

    public function setOriginalStudentClass(?string $originalStudentClass): static
    {
        $this->originalStudentClass = $originalStudentClass;

        return $this;
    }

    public function getEffectiveRole(): ?string
    {
        return $this->effectiveRole ?? null;
    }

    public function setEffectiveRole(?string $effectiveRole): static
    {
        $this->effectiveRole = $effectiveRole;

        return $this;
    }

    public function getEffectiveStudentClass(): ?string
    {
        return $this->effectiveStudentClass ?? null;
    }

    public function setEffectiveStudentClass(?string $effectiveStudentClass): static
    {
        $this->effectiveStudentClass = $effectiveStudentClass;

        return $this;
    }

    public function getRealRole(): string
    {
        return $this->getEffectiveRole() ?? $this->getOriginalRole();
    }

    public function getRealStudentClass(): ?string
    {
        if ($this->getRealRole() !== 'ROLE_STUDENT') {
            return null;
        }
        return $this->getEffectiveStudentClass() ?? ($this->getOriginalStudentClass() ?? '?');
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
        return [$this->getRealRole()];
    }

    public function getFundamentalRoleGains(): array
    {
        return [
            [$this->getOriginalRole(), $this->getRealRole()],
        ];
    }
}
