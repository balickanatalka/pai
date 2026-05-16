<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Loggable;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[Gedmo\Loggable(logEntryClass: LogEntry::class)]
class Employee implements Loggable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Gedmo\Versioned]
    private ?string $firstName = null;

    #[ORM\Column(length: 30)]
    #[Gedmo\Versioned]
    private ?string $lastName = null;

    #[ORM\Column(length: 30)]
    #[Gedmo\Versioned]
    private ?string $employeeNumber = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $department = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $position = null;

    #[ORM\Column]
    #[Gedmo\Versioned]
    private ?\DateTimeImmutable $hiredAt = null;

    #[ORM\Column]
    #[Gedmo\Versioned]
    private ?bool $isActive = null;

    #[ORM\OneToOne(inversedBy: 'employee', cascade: ['persist'])]
    #[ORM\JoinColumn(
        name: 'app_user_id',
        referencedColumnName: 'id',
        nullable: true,
        onDelete: 'SET NULL'
    )]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = trim($firstName);

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = trim($lastName);

        return $this;
    }

    public function getEmployeeNumber(): ?string
    {
        return $this->employeeNumber;
    }

    public function setEmployeeNumber(string $employeeNumber): static
    {
        $this->employeeNumber = trim($employeeNumber);

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): static
    {
        $this->department = $department !== null ? trim($department) : null;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): static
    {
        $this->position = $position !== null ? trim($position) : null;

        return $this;
    }

    public function getHiredAt(): ?\DateTimeImmutable
    {
        return $this->hiredAt;
    }

    public function setHiredAt(\DateTimeImmutable $hiredAt): static
    {
        $this->hiredAt = $hiredAt;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        if ($user !== null && $user->getEmployee() !== $this) {
            $user->setEmployee($this);
        }

        return $this;
    }
}
