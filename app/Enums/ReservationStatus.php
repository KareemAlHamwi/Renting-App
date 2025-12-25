<?php

namespace App\Enums;

enum ReservationStatus: int {
    case Pending   = 1;
    case Reserved  = 2;
    case Completed = 3;
    case Cancelled = 4;

    public function label(): string {
        return match ($this) {
            self::Pending   => 'pending',
            self::Reserved  => 'reserved',
            self::Completed => 'completed',
            self::Cancelled => 'cancelled',
        };
    }
}
