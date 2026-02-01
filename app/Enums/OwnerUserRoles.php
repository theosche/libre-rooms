<?php

namespace App\Enums;

enum OwnerUserRoles: string
{
    case VIEWER = 'viewer';
    case MODERATOR = 'moderator';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::VIEWER => 'Lecteur·ice',
            self::MODERATOR => 'Modérateur·ice',
            self::ADMIN => 'Administrateur·ice',
        };
    }
    public function label_short(): string
    {
        return match ($this) {
            self::VIEWER => 'Lect.',
            self::MODERATOR => 'Mod.',
            self::ADMIN => 'Admin',
        };
    }

    /**
     * Check if this role has at least the given permission level.
     */
    public function hasAtLeast(self $role): bool
    {
        return $this->weight() >= $role->weight();
    }

    /**
     * Get the weight for permission comparison.
     */
    private function weight(): int
    {
        return match ($this) {
            self::VIEWER => 1,
            self::MODERATOR => 2,
            self::ADMIN => 3,
        };
    }
}
