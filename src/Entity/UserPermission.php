<?php

namespace App\Entity;

use App\Repository\UserPermissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserPermissionRepository::class)]
#[ORM\Table(name: 'user_permission')]
#[ORM\UniqueConstraint(name: 'uniq_user_permission', columns: ['user_id', 'permission_id'])]
class UserPermission
{
    public const ALLOW = 1;
    public const DENY = -1;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userPermissionOverrides')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Permission::class, inversedBy: 'userOverrides')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Permission $permission = null;

    #[ORM\Column(type: 'smallint')]
    private int $effect = self::ALLOW;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPermission(): ?Permission
    {
        return $this->permission;
    }

    public function setPermission(?Permission $permission): self
    {
        $this->permission = $permission;

        return $this;
    }

    public function getEffect(): int
    {
        return $this->effect;
    }

    public function setEffect(int $effect): self
    {
        if (!in_array($effect, [self::ALLOW, self::DENY], true)) {
            throw new \InvalidArgumentException('Invalid permission effect.');
        }

        $this->effect = $effect;

        return $this;
    }

    public function isAllowed(): bool
    {
        return $this->effect === self::ALLOW;
    }

    public function isDenied(): bool
    {
        return $this->effect === self::DENY;
    }
}
