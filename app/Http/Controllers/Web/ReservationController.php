<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property\Property;
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
}
