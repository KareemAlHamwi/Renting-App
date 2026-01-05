<?php

namespace App\Repositories\Eloquent\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Property\Property;
use App\Models\Reservation\Reservation;
use App\Models\User\User;
use App\Repositories\Contracts\Reservation\ReservationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class ReservationRepository implements ReservationRepositoryInterface {
    public function getAllReservations(array $filters = []) {
        $q         = trim((string)($filters['q'] ?? ''));
        $status    = $filters['status'] ?? null;
        $dateRange = $filters['date_range'] ?? null;     // NEW
        $perPage   = (int)($filters['per_page'] ?? 10);

        $perPage = in_array($perPage, [10, 15, 20, 50], true) ? $perPage : 10;

        $query = Reservation::query()
            ->with([
                'user.person',
                'property.owner.person',
                'property.governorate',
                'property.primaryPhoto',
                'review',
            ])
            ->orderByDesc('start_date');

        // NEW: date range filter (applied on start_date; change to created_at if you prefer)
        if (!empty($dateRange)) {
            [$from, $to] = $this->resolveDateRange($dateRange);

            if ($from && $to) {
                $query->whereBetween('start_date', [$from, $to]);
            }
        }

        // Search: tenant/landlord only (no date parsing in q anymore)
        if ($q !== '') {
            $query->where(function (Builder $sub) use ($q) {
                $sub->whereHas('user', function (Builder $u) use ($q) {
                    $u->where('username', 'like', "%{$q}%")
                        ->orWhere('phone_number', 'like', "%{$q}%")
                        ->orWhereHas('person', function (Builder $p) use ($q) {
                            $p->where('first_name', 'like', "%{$q}%")
                                ->orWhere('last_name', 'like', "%{$q}%");
                        });
                })
                    ->orWhereHas('property.owner', function (Builder $u) use ($q) {
                        $u->where('username', 'like', "%{$q}%")
                            ->orWhere('phone_number', 'like', "%{$q}%")
                            ->orWhereHas('person', function (Builder $p) use ($q) {
                                $p->where('first_name', 'like', "%{$q}%")
                                    ->orWhere('last_name', 'like', "%{$q}%");
                            });
                    });
            });
        }

        switch ($status) {
            case 'pending':
                $query->where('status', ReservationStatus::Pending);
                break;
            case 'reserved':
                $query->where('status', ReservationStatus::Reserved);
                break;
            case 'completed':
                $query->where('status', ReservationStatus::Completed);
                break;
            case 'cancelled':
                $query->where('status', ReservationStatus::Cancelled);
                break;
            default:
                break;
        }

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Returns [from, to] as Carbon instances (inclusive).
     * Applies to start_date by default.
     */
    private function resolveDateRange(string $range): array {
        $now = now();

        return match ($range) {
            'today' => [
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            'last_week' => [
                $now->copy()->subWeek()->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            'last_month' => [
                $now->copy()->subMonth()->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            'last_3_months' => [
                $now->copy()->subMonths(3)->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            'last_6_months' => [
                $now->copy()->subMonths(6)->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            'last_year' => [
                $now->copy()->subYear()->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            default => [null, null],
        };
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
        Property::query()
            ->whereKey($property->id)
            ->lockForUpdate()
            ->first();

        return Reservation::query()
            ->where('property_id', $property->id)
            ->whereIn('status', [
                ReservationStatus::Pending,
                ReservationStatus::Reserved,
            ])
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->exists();
    }

    public function checkConflictExceptReservation(Property $property, string $startDate, string $endDate, Reservation $ignoreReservation) {
        Property::query()
            ->whereKey($property->id)
            ->lockForUpdate()
            ->first();

        return Reservation::query()
            ->where('property_id', $property->id)
            ->whereIn('status', [
                ReservationStatus::Pending,
                ReservationStatus::Reserved,
            ])
            ->whereKeyNot($ignoreReservation->id)
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
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
