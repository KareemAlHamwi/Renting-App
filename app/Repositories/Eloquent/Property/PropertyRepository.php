<?php

namespace App\Repositories\Eloquent\Property;

use App\Models\Property\Property;
use App\Models\User\User;
use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PropertyRepository implements PropertyRepositoryInterface {
    public function getAll() {
        return Property::query()
            ->with(['photos', 'governorate'])
            ->orderByDesc('id')
            ->get();
    }

    public function getAllVerified(User $user) {
        return Property::query()
            ->with(['photos', 'governorate'])
            ->whereNotNull('verified_at')
            ->where('user_id', '!=', $user->id)
            ->orderByDesc('id')
            ->get();
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
        // Use the provided instance; do not re-query unless you truly need fresh state.
        $property->fill($data);

        if ($property->isDirty()) {
            // Business rule: changing anything invalidates verification
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
        return [
            'count' => (int) ($property->reviewers_number ?? 0),
            'avg'   => (float) ($property->overall_reviews ?? 0),
        ];
    }

    /**
     * Transactions moved into repository to keep the service layer free of DB facade usage.
     */
    public function applyReviewStatsAdd(Property $property, float $rating) {
        DB::transaction(function () use ($property, $rating) {
            $locked = $this->findLockedOrFail($property);

            ['count' => $count, 'avg' => $avg] = $this->getCurrentReviewStats($locked);

            $newCount = $count + 1;
            $newAvg   = (($avg * $count) + $rating) / $newCount;

            $this->saveReviewStats($locked, $newCount, $this->normalizeAvg($newAvg));
        });
    }

    public function applyReviewStatsReplace(Property $property, float $oldRating, float $newRating) {
        DB::transaction(function () use ($property, $oldRating, $newRating) {
            $locked = $this->findLockedOrFail($property);

            ['count' => $count, 'avg' => $avg] = $this->getCurrentReviewStats($locked);

            if ($count <= 0) {
                $this->resetReviewStats($locked);
                return;
            }

            $newAvg = (($avg * $count) - $oldRating + $newRating) / $count;

            $this->saveReviewStats($locked, $count, $this->normalizeAvg($newAvg));
        });
    }

    public function applyReviewStatsRemove(Property $property, float $rating) {
        DB::transaction(function () use ($property, $rating) {
            $locked = $this->findLockedOrFail($property);

            ['count' => $count, 'avg' => $avg] = $this->getCurrentReviewStats($locked);

            if ($count <= 1) {
                $this->resetReviewStats($locked);
                return;
            }

            $newCount = $count - 1;
            $newAvg   = (($avg * $count) - $rating) / $newCount;

            $this->saveReviewStats($locked, $newCount, $this->normalizeAvg($newAvg));
        });
    }

    private function normalizeAvg(float $avg) {
        return round(max(0, $avg), 2);
    }
}
