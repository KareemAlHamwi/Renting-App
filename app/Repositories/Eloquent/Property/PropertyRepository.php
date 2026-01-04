<?php

namespace App\Repositories\Eloquent\Property;

use App\Models\Property\Property;
use App\Models\User\User;
use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class PropertyRepository implements PropertyRepositoryInterface {
    public function getAll() {
        return Property::query()
            ->with(['photos', 'governorate'])
            ->orderByDesc('id')
            // ->paginate(10);
            ->get();
    }

    // public function getVerifiedExcludingUser(User $user) {
    //     return Property::query()
    //         ->with(['photos', 'governorate'])
    //         ->whereNotNull('verified_at')
    //         ->where('user_id', '!=', $user->id)
    //         ->orderByDesc('id')
    //         ->get();
    // }

    public function getVerifiedExcludingUser(User $user, array $filters = []) {
        $query = Property::query()
            ->with(['photos', 'governorate'])
            ->whereNotNull('verified_at')
            ->where('user_id', '!=', $user->id);

        // title
        $query->when(
            filled($filters['title'] ?? null),
            fn(Builder $q) => $q->where('title', 'like', '%' . $filters['title'] . '%')
        );

        // governorate_id
        $query->when(
            filled($filters['governorate_id'] ?? null),
            fn(Builder $q) => $q->where('governorate_id', (int) $filters['governorate_id'])
        );

        // rent_min / rent_max (already normalized)
        $rentMin = $filters['rent_min'] ?? null;
        $rentMax = $filters['rent_max'] ?? null;

        if ($rentMin !== null && $rentMax !== null) {
            $query->whereBetween('rent', [$rentMin, $rentMax]);
        } elseif ($rentMin !== null) {
            $query->where('rent', '>=', $rentMin);
        } elseif ($rentMax !== null) {
            $query->where('rent', '<=', $rentMax);
        }

        // sorting (already normalized)
        $sortBy  = $filters['sort_by'] ?? 'verified_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        // pagination (already normalized)
        $perPage = (int) ($filters['per_page'] ?? 15);

        return $query
            ->paginate($perPage)   // uses ?page= automatically
            ->withQueryString();   // keeps filters/sort/per_page in pagination links
    }

    public function getUserProperties(User $user) {
        return Property::query()
            ->with(['photos', 'governorate'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    }

    public function getUserProperty(User $user, Property $property) {
        return Property::query()
            ->with(['photos', 'governorate'])
            ->whereKey($property->getKey())
            ->where('user_id', $user->id)
            ->firstOrFail();
    }

    public function create(array $data) {
        return Property::create($data);
    }

    public function findById(Property $property) {
        return Property::query()
            ->with(['photos', 'governorate'])
            ->whereKey($property->getKey())
            ->firstOrFail();
    }

    public function update(Property $property, array $data) {
        $property->fill($data);

        if ($property->isDirty()) {
            $property->verified_at = null;
        }

        $property->save();

        return $property->fresh(['photos', 'governorate']);
    }

    public function delete(Property $property) {
        return Property::query()
            ->whereKey($property->getKey())
            ->delete();
    }

    public function markAsVerified(Property $property) {
        $property->forceFill(['verified_at' => now()])->save();

        return $property->fresh(['photos', 'governorate']);
    }

    public function findLockedOrFail(Property $property) {
        return Property::query()
            ->whereKey($property->getKey())
            ->lockForUpdate()
            ->firstOrFail();
    }

    public function saveReviewStats(Property $property, int $reviewersNumber, float $overallReviews) {
        $property->forceFill([
            'reviewers_number' => $reviewersNumber,
            'overall_reviews'  => $overallReviews,
        ])->save();

        return $property;
    }

    public function resetReviewStats(Property $property) {
        return $this->saveReviewStats($property, 0, 0.0);
    }

    public function getCurrentReviewStats(Property $property) {
        $fresh = Property::query()
            ->select(['id', 'reviewers_number', 'overall_reviews'])
            ->whereKey($property->getKey())
            ->first();

        return [
            'count' => (int) ($fresh?->reviewers_number ?? 0),
            'avg'   => (float) ($fresh?->overall_reviews ?? 0.0),
        ];
    }
}
