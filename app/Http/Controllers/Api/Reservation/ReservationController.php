<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\AddReviewToReservationRequest;
use App\Http\Requests\Reservation\StoreUpdateReservationRequest;
use App\Http\Resources\Reservation\ReservationResource;
use App\Http\Resources\Reservation\ReservedPeriodResource;
use App\Http\Resources\Reservation\ReviewResource;
use App\Models\Property\Property;
use App\Models\Reservation\Reservation;
use App\Services\Property\PropertyService;
use App\Services\Reservation\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller {
    private ReservationService $reservationService;
    private PropertyService $propertyService;
    public function __construct(ReservationService $reservationService, PropertyService $propertyService) {
        $this->reservationService = $reservationService;
        $this->propertyService = $propertyService;
    }

    public function landlordPropertyReservations(Request $request, Property $property) {
        $this->authorize('landlordPropertyReservations', Reservation::class);

        $landlord = $request->user();

        $reservations = $this->reservationService->getLandlordPropertyReservations($landlord, $property);

        return ReservationResource::collection($reservations);
    }

    public function tenantReservations(Request $request) {
        $this->authorize('tenantReservations', Reservation::class);

        $user = $request->user();

        $reservations = $this->reservationService->getTenantReservations($user);

        return ReservationResource::collection($reservations);
    }

    public function store(StoreUpdateReservationRequest $request, Property $property) {
        $this->authorize('create', [Reservation::class, $property]);

        $user = $request->user();

        $reservation = $this->reservationService->createReservation(
            $user,
            $property,
            $request->validated('start_date'),
            $request->validated('end_date')
        );

        return new ReservationResource($reservation);
    }

    public function update(StoreUpdateReservationRequest $request, Reservation $reservation) {
        $this->authorize('update', $reservation);

        $updated = $this->reservationService->updateReservation($reservation,$request->validated());

        return new ReservationResource($updated);
    }

    public function approve(Reservation $reservation) {
        $this->authorize('approve', $reservation);

        $reservation = $this->reservationService->approveReservation($reservation);

        return new ReservationResource($reservation);
    }

    public function cancel(Request $request, Reservation $reservation) {
        $this->authorize('cancel', $reservation);

        $user = $request->user();

        $reservation = $this->reservationService->cancelReservation($reservation, $user);

        return new ReservationResource($reservation);
    }

    public function addReview(AddReviewToReservationRequest $request, Reservation $reservation) {
        $this->authorize('addReview', $reservation);

        $user = $request->user();
        $reviewData = $request->validated();

        $review = $this->reservationService->addReviewToReservation($user,$reservation,$reviewData);

        $this->propertyService->addReviewStats($reservation, $reviewData);

        return new ReviewResource($review);
    }

    public function reservedPeriods(Property $property) {
        $periods = $this->reservationService->getReservedPeriods($property);

        return ReservedPeriodResource::collection($periods);
    }
}
