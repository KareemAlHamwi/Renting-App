<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\AddReviewToReservationRequest;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Resources\Reservation\ReservationResource;
use App\Http\Resources\Reservation\ReservedPeriodResource;
use App\Http\Resources\Reservation\ReviewResource;
use App\Models\Reservation\Reservation;
use App\Services\Reservation\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller {
    private ReservationService $reservationService;
    public function __construct(ReservationService $reservationService) {
        $this->reservationService = $reservationService;
    }

    public function landlordPropertyReservations(Request $request, int $propertyId) {
        $this->authorize('landlordPropertyReservations', Reservation::class);

        $landlordUserId = (int) $request->user()->id;

        $reservations = $this->reservationService->getLandlordPropertyReservations(
            landlordUserId: $landlordUserId,
            propertyId: $propertyId
        );

        return ReservationResource::collection($reservations);
    }

    public function tenantReservations(Request $request) {
        $this->authorize('tenantReservations', Reservation::class);

        $userId = (int) $request->user()->id;

        $reservations = $this->reservationService->getTenantReservations($userId);

        return ReservationResource::collection($reservations);
    }

    public function store(StoreReservationRequest $request) {
        $this->authorize('create', Reservation::class);

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
        $this->authorize('addReview', [Reservation::class, $id]);

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
