<?php

namespace App\Console\Commands;

use App\Services\Reservation\ReservationService;
use Illuminate\Console\Command;

class MarkExpiredReservationsCompleted extends Command {
    protected $signature = 'reservations:mark-completed';
    protected $description = 'Mark expired reserved reservations as completed';

    public function handle(ReservationService $service) {
        $count = $service->markExpiredReservationsCompleted();
        $this->info("Marked {$count} reservations as completed.");
        return self::SUCCESS;
    }
}
