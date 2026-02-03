<?php

namespace App\Enums;

enum InvoiceDueModes: string
{
    case BEFORE_EVENT = 'before_event';
    case AFTER_EVENT = 'after_event';
    case AFTER_CONFIRM = 'after_confirm';

    public function label(?int $days = null): string
    {
        $d = $days ?? 'X';

        return match ($this) {
            self::BEFORE_EVENT => __('Invoice due :days days before the first reservation date', ['days' => $d]),
            self::AFTER_EVENT => __('Invoice due :days days after the first reservation date', ['days' => $d]),
            self::AFTER_CONFIRM => __('Invoice due :days days after reservation confirmation', ['days' => $d]),
        };
    }

    public function shortLabel(?int $days = null): string
    {
        $d = $days ?? 'X';

        return match ($this) {
            self::BEFORE_EVENT => __(':days days before event', ['days' => $d]),
            self::AFTER_EVENT => __(':days days after event', ['days' => $d]),
            self::AFTER_CONFIRM => __(':days days', ['days' => $d]),
        };
    }
}
