<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\AddReviewToReservationRequest;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Resources\Reservation\ReservationResource;
use App\Http\Resources\Reservation\ReservedPeriodResource;
use App\Http\Resources\Reservation\ReviewResource;
use App\Services\Reservation\ReservationService;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller {
    private ReservationService $reservationService;
    public function __construct(ReservationService $reservationService) {
        $this->reservationService = $reservationService;
    }

    public function store(StoreReservationRequest $request) {
        $userId = (int) $request->user()->id;

        $reservation = $this->reservationService->createReservation(
            userId: $userId,
            propertyId: (int) $request->validated('property_id'),
            startDate: $request->validated('start_date'),
            endDate: $request->validated('end_date'),
        );

        return new ReservationResource($reservation);
    }

    public function addReview(AddReviewToReservationRequest $request, int $id) {
        $userId = (int) $request->user()->id;

        $review = $this->reservationService->addReviewToReservation(
            userId: $userId,
            reservationId: $id,
            reviewData: $request->validated(),
        );

        return new ReviewResource($review);
    }

    public function reservedPeriods(int $id) {
        $periods = $this->reservationService->getReservedPeriods($id);

        return ReservedPeriodResource::collection($periods);
    }
}
