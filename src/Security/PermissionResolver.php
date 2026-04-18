<?php

namespace App\Security;

use App\Entity\Permission;
use App\Entity\User;
use App\Entity\UserPermission;

class PermissionResolver
{
    /**
     * Checks whether a user has a given permission.
     *
     * Resolution order:
     * 1. ROLE_ADMIN shortcut (full access)
     * 2. User-specific overrides (ALLOW / DENY)
     * 3. Permissions inherited from roles
     * 4. Default: deny
     */
    public function hasPermission(User $user, string $permissionCode): bool
    {
        $permissionCode = strtolower(trim($permissionCode));

        if ($permissionCode === '') {
            return false;
        }

        // 1. Global shortcut for admin users
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // 2. Check user-specific overrides (highest priority after admin)
        foreach ($user->getUserPermissionOverrides() as $userPermission) {
            $permission = $userPermission->getPermission();

            if (!$permission instanceof Permission) {
                continue;
            }

            if ($permission->getCode() !== $permissionCode) {
                continue;
            }

            // ALLOW = true, DENY = false
            return $userPermission->isAllowed();
        }

        // 3. Check permissions inherited from roles
        foreach ($user->getUserRoles() as $role) {
            foreach ($role->getPermissions() as $permission) {
                if ($permission->getCode() === $permissionCode) {
                    return true;
                }
            }
        }

        // 4. No match = access denied
        return false;
    }

    /**
     * Checks if the user has at least one of the given permissions.
     */
    public function hasAnyPermission(User $user, array $permissionCodes): bool
    {
        foreach ($permissionCodes as $permissionCode) {
            if ($this->hasPermission($user, (string) $permissionCode)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the user has all of the given permissions.
     */
    public function hasAllPermissions(User $user, array $permissionCodes): bool
    {
        foreach ($permissionCodes as $permissionCode) {
            if (!$this->hasPermission($user, (string) $permissionCode)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns a list of effective permissions for the user.
     *
     * Includes:
     * - permissions from roles
     * - user-specific overrides
     *
     * Note: DENY overrides remove permissions from the final set.
     */
    public function getEffectivePermissions(User $user): array
    {
        $allowed = [];
        $denied = [];

        // Collect permissions from roles
        foreach ($user->getUserRoles() as $role) {
            foreach ($role->getPermissions() as $permission) {
                $allowed[$permission->getCode()] = true;
            }
        }

        // Apply user-specific overrides
        foreach ($user->getUserPermissionOverrides() as $userPermission) {
            $permission = $userPermission->getPermission();

            if (!$permission instanceof Permission) {
                continue;
            }

            $code = $permission->getCode();

            if ($userPermission->getEffect() === UserPermission::ALLOW) {
                $allowed[$code] = true;
                unset($denied[$code]);
                continue;
            }

            if ($userPermission->getEffect() === UserPermission::DENY) {
                $denied[$code] = true;
                unset($allowed[$code]);
            }
        }

        // Admin has implicit full access
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $allowed['*'] = true;
        }

        return array_keys($allowed);
    }
}
