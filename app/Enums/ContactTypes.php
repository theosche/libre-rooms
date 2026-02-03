<?php

namespace App\Enums;

enum ContactTypes: string
{
    case INDIVIDUAL = 'individual';
    case ORGANIZATION = 'organization';

    public function label(): string
    {
        return match ($this) {
            self::INDIVIDUAL => __('Private'),
            self::ORGANIZATION => __('Organization'),
        };
    }
}
