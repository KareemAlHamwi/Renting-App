<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Reservation\ReservationResource;
use App\Models\Reservation\Reservation;
use App\Services\Reservation\ReservationService;

class ReservationController extends Controller {
    private ReservationService $reservationService;

    public function __construct(ReservationService $reservationService) {
        $this->reservationService = $reservationService;
    }

    public function index() {
        $reservations = $this->reservationService->getAllReservations();
        return view('reservations.index', compact('reservations'));
    }

    public function cancel(int $id) {
        $this->reservationService->cancelReservation($id);

        return redirect()
            ->back()
            ->with('success', 'Reservation cancelled successfully.');
    }
}
