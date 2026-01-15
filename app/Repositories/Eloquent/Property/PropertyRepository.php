<?php

namespace App\Repositories\Eloquent\Property;

use App\Models\Property\Property;
use App\Models\User\User;
use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

use function Illuminate\Support\now;

class PropertyRepository implements PropertyRepositoryInterface {
    public function getAll(array $filters = []) {
        $q             = trim((string)($filters['q'] ?? ''));
        $governorateId = $filters['governorate_id'] ?? null;
        $status        = $filters['status'] ?? null;
        $publishment        = $filters['publishment'] ?? null;
        $perPage       = (int)($filters['per_page'] ?? 10);

        $allowedSortBy = ['id', 'rent', 'overall_reviews', 'verified_at', 'reviewers_number'];
        $sortBy  = (string)($filters['sort_by'] ?? 'id');
        $sortBy  = in_array($sortBy, $allowedSortBy, true) ? $sortBy : 'id';

        $sortDir = strtolower((string)($filters['sort_dir'] ?? 'desc'));
        $sortDir = in_array($sortDir, ['asc', 'desc'], true) ? $sortDir : 'desc';

        $query = Property::query()
            ->with(['photos', 'governorate']);

        if ($q !== '') {
            $query->where(function (Builder $sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%");
            });
        }

        if (!empty($governorateId)) {
            $query->where('governorate_id', (int)$governorateId);
        }

        if ($status === 'verified') {
            $query->whereNotNull('verified_at');
        } elseif ($status === 'pending') {
            $query->whereNull('verified_at');
        }

        if ($publishment === 'published') {
            $query->whereNotNull('published_at');
        } elseif ($publishment === 'unpublished') {
            $query->whereNull('published_at');
        }

        return $query
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getVerifiedExcludingUser(User $user, array $filters = []) {
        $query = Property::query()
            ->with(['photos', 'governorate'])
            ->whereNotNull('verified_at')
            ->whereNotNull('published_at')
            ->where('user_id', '!=', $user->id);

        $query->when(
            filled($filters['title'] ?? null),
            fn(Builder $q) => $q->where('title', 'like', '%' . $filters['title'] . '%')
        );

        $query->when(
            filled($filters['governorate_id'] ?? null),
            fn(Builder $q) => $q->where('governorate_id', (int) $filters['governorate_id'])
        );

        $rentMin = $filters['rent_min'] ?? null;
        $rentMax = $filters['rent_max'] ?? null;

        if ($rentMin !== null && $rentMax !== null) {
            $query->whereBetween('rent', [$rentMin, $rentMax]);
        } elseif ($rentMin !== null) {
            $query->where('rent', '>=', $rentMin);
        } elseif ($rentMax !== null) {
            $query->where('rent', '<=', $rentMax);
        }

        $allowedSortBy = ['rent', 'overall_reviews', 'reviewers_number', 'published_at'];
        $sortBy  = (string)($filters['sort_by'] ?? 'id');
        $sortBy  = in_array($sortBy, $allowedSortBy, true) ? $sortBy : 'published_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        $perPage = (int) ($filters['per_page'] ?? 15);

        return $query
            ->paginate($perPage)
            ->withQueryString();
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

    public function findById(int $propertyId) {
        return Property::query()
            ->with(['photos', 'governorate'])
            ->whereKey($propertyId)
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

    public function publish(Property $property) {
        $property->forceFill(['published_at' => now()])->save();
    }

    public function unpublish(Property $property) {
        $property->forceFill(['published_at' => null])->save();
    }

    public function isPublished(Property $property) {
        return Property::query()
            ->where('id', $property->id)
            ->whereNotNull('published_at')
            ->exists();
    }

    public function markAsVerified(Property $property) {
        $property->forceFill(['verified_at' => now()])->save();
        $property->forceFill(['published_at' => now()])->save();

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
