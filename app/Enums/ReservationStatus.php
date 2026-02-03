<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case FINISHED = 'finished';

    public function icalStatus(): string
    {
        return match ($this) {
            self::PENDING => "PENDING",
            self::CONFIRMED, self::FINISHED => "CONFIRMED",
            self::CANCELLED => "CANCELLED",
        };
    }
    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::CONFIRMED => __('Confirmed'),
            self::FINISHED => __('Finished'),
            self::CANCELLED => __('Cancelled.m'),
        };
    }
}
