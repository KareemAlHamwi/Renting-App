<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reservation\Reservation;
use App\Services\Reservation\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller {
    private ReservationService $reservationService;

    public function __construct(ReservationService $reservationService) {
        $this->reservationService = $reservationService;
    }

    public function index(Request $request) {
        $filters = $request->only([
            'q',
            'status',
            'date_range',
            'per_page'
        ]);

        $reservations = $this->reservationService->getAllReservations($filters);

        return view('reservations.index', compact('reservations'));
    }

    public function cancel(Reservation $reservation) {
        $this->reservationService->cancelReservation($reservation,Auth::user());

        return redirect()
            ->back()
            ->with('success', 'Reservation cancelled successfully.');
    }
}
