<?php

namespace App\Enums;

enum CustomFieldTypes: string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case SELECT = 'select';
    case CHECKBOX = 'checkbox';
    case RADIO = 'radio';

    public function label(): string
    {
        return match ($this) {
            self::TEXT => __('Text'),
            self::TEXTAREA => __('Text area'),
            self::SELECT => __('Dropdown'),
            self::CHECKBOX => __('Checkbox'),
            self::RADIO => __('Radio buttons'),
        };
    }
}
