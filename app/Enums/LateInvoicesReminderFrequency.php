<?php

namespace App\Enums;

enum LateInvoicesReminderFrequency: string
{
    case NEVER = 'never';
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';

    public function label(): string
    {
        return match ($this) {
            self::NEVER => __('Never'),
            self::DAILY => __('Daily'),
            self::WEEKLY => __('Weekly'),
            self::MONTHLY => __('Monthly'),
        };
    }

    /**
     * Check if a reminder should be sent based on the last sent date.
     */
    public function shouldSendReminder(?\DateTimeInterface $lastSentAt): bool
    {
        if ($this === self::NEVER) {
            return false;
        }

        if ($lastSentAt === null) {
            return true;
        }

        $now = now()->startOfDay();
        $lastSent = \Carbon\Carbon::parse($lastSentAt)->startOfDay();

        return match ($this) {
            self::DAILY => $lastSent->lt($now),
            self::WEEKLY => $lastSent->addWeek()->lte($now),
            self::MONTHLY => $lastSent->addMonth()->lte($now),
            default => false,
        };
    }
}
