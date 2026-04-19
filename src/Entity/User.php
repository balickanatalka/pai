<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'app_user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column]
    private string $password;

    #[ORM\Column(options: ['default' => true])]
    private bool $isActive = true;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'user_role')]
    private Collection $userRoles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserPermission::class, orphanRemoval: true)]
    private Collection $userPermissionOverrides;

    #[ORM\OneToOne(mappedBy: 'appUser', cascade: ['persist', 'remove'])]
    private ?Employee $employee = null;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->userPermissionOverrides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = strtolower(trim($email));

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        foreach ($this->userRoles as $role) {
            $roles[] = $role->getCode();
        }

        return array_values(array_unique($roles));
    }

    /**
     * @return Collection<int, Role>
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $role): self
    {
        if (!$this->userRoles->contains($role)) {
            $this->userRoles->add($role);
        }

        return $this;
    }

    public function removeUserRole(Role $role): self
    {
        $this->userRoles->removeElement($role);

        return $this;
    }

    /**
     * @return Collection<int, UserPermission>
     */
    public function getUserPermissionOverrides(): Collection
    {
        return $this->userPermissionOverrides;
    }

    public function addUserPermissionOverride(UserPermission $userPermission): self
    {
        if (!$this->userPermissionOverrides->contains($userPermission)) {
            $this->userPermissionOverrides->add($userPermission);
            $userPermission->setUser($this);
        }

        return $this;
    }

    public function removeUserPermissionOverride(UserPermission $userPermission): self
    {
        if ($this->userPermissionOverrides->removeElement($userPermission)) {
            if ($userPermission->getUser() === $this) {
                $userPermission->setUser(null);
            }
        }

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        // unset the owning side of the relation if necessary
        if ($employee === null && $this->employee !== null) {
            $this->employee->setAppUser(null);
        }

        // set the owning side of the relation if necessary
        if ($employee !== null && $employee->getAppUser() !== $this) {
            $employee->setAppUser($this);
        }

        $this->employee = $employee;

        return $this;
    }
}
