<?php

namespace App\Repositories\Eloquent\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Property\Property;
use App\Models\Reservation\Reservation;
use App\Models\User\User;
use App\Repositories\Contracts\Reservation\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface {
    public function getAllReservations() {
        return Reservation::query()
            ->with([
                'user',
                'property.owner',
                'property.governorate',
                'property.primaryPhoto',
                'review',
            ])
            ->orderByDesc('start_date')
            ->get();
    }

    public function getLandlordPropertyReservations(User $landlord, Property $property) {
        return Reservation::query()
            ->with(['user', 'review'])
            ->where('property_id', $property->id)
            ->whereHas('property', function ($q) use ($landlord) {
                $q->where('user_id', $landlord->id);
            })
            ->orderByDesc('start_date')
            ->get();
    }

    public function getTenantReservations(User $tenant) {
        return Reservation::query()
            ->with([
                'property.governorate',
                'property.primaryPhoto',
                'review',
            ])
            ->where('user_id', $tenant->id)
            ->orderByDesc('start_date')
            ->get();
    }

    public function findById(Reservation $reservation) {
        return Reservation::query()->findOrFail($reservation->id);
    }

    public function createReservation(array $data) {
        return Reservation::query()->create($data);
    }

    public function updateReservation(Reservation $reservation, array $data) {
        $reservation->update($data);
        return $reservation->refresh();
    }

    public function approveReservation(Reservation $reservation) {
        return $this->updateReservation($reservation, [
            'status' => ReservationStatus::Reserved,
        ]);
    }

    public function cancelReservation(Reservation $reservation, User $cancelledBy) {
        return $this->updateReservation($reservation, [
            'status'       => ReservationStatus::Cancelled,
            'cancelled_by' => $cancelledBy->id,
        ]);
    }

    public function markExpiredReservationsCompleted() {
        return Reservation::query()
            ->where('status', ReservationStatus::Reserved)
            ->whereDate('end_date', '<', now()->toDateString())
            ->update(['status' => ReservationStatus::Completed]);
    }

    public function checkConflict(Property $property, string $startDate, string $endDate) {
        return Reservation::query()
            ->where('property_id', $property->id)
            ->whereIn('status', [
                ReservationStatus::Pending,
                ReservationStatus::Reserved,
            ])
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->exists();
    }

    public function checkConflictExceptReservation(Property $property,string $startDate,string $endDate,Reservation $ignoreReservation) {
        return Reservation::query()
            ->where('property_id', $property->id)
            ->whereIn('status', [
                ReservationStatus::Pending,
                ReservationStatus::Reserved,
            ])
            ->whereKeyNot($ignoreReservation->id)
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->exists();
    }

    public function getReservedPeriods(Property $property) {
        return Reservation::query()
            ->where('property_id', $property->id)
            ->whereIn('status', [
                ReservationStatus::Pending,
                ReservationStatus::Reserved,
            ])
            ->orderBy('start_date')
            ->get(['start_date', 'end_date']);
    }
}
