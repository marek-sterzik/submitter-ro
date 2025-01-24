<?php

namespace App\Entity;

use App\Repository\AssignmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssignmentRepository::class)]
class Assignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private ?string $caption = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $classes = null;

    #[ORM\Column(nullable: true)]
    private ?int $schoolYear = null;

    #[ORM\Column]
    private bool $public = false;

    #[ORM\Column]
    private bool $published = false;

    #[ORM\ManyToOne(inversedBy: 'ownedAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private User $owner;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $softDeadline = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $hardDeadline = null;


    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): static
    {
        $this->caption = $caption;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getClasses(): ?string
    {
        return $this->classes;
    }

    public function setClasses(string $classes): static
    {
        $this->classes = $classes;

        return $this;
    }

    public function getSchoolYear(): ?int
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(?int $schoolYear): static
    {
        $this->schoolYear = $schoolYear;

        return $this;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): static
    {
        $this->public = $public;

        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSoftDeadline(): ?\DateTimeImmutable
    {
        return $this->softDeadline;
    }

    public function setSoftDeadline(?\DateTimeImmutable $softDeadline): static
    {
        $this->softDeadline = $softDeadline;

        return $this;
    }

    public function getHardDeadline(): ?\DateTimeImmutable
    {
        return $this->hardDeadline;
    }

    public function setHardDeadline(?\DateTimeImmutable $hardDeadline): static
    {
        $this->hardDeadline = $hardDeadline;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }
}
