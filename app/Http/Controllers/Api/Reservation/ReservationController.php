<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Services\Reservation\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller {
    protected $reservationService;

    public function __construct(ReservationService $reservationService) {
        $this->reservationService = $reservationService;
    }


    public function store(Request $request) {
        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'property_id' => 'required|exists:properties,id',
            'user_id'    => 'required|exists:users,id',
        ]);

        $reservation = $this->reservationService->createReservation($data);

        return response()->json($reservation, 201);
    }

    public function addReview(Request $request, $id) {
        $data = $request->validate([
            'stars'  => 'required|numeric|min:0|max:5',
            'review' => 'nullable|string',
        ]);

        $review = $this->reservationService->addReviewToReservation($id, $data);

        return response()->json($review, 201);
    }
}
