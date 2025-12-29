<?php

namespace App\Repositories\Eloquent\Property;

use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use App\Models\Property\Property;
use Illuminate\Support\Facades\DB;

class PropertyRepository implements PropertyRepositoryInterface {
    public function getAll() {
        return Property::with('photos', 'governorate')->orderByDesc('id')->get();
    }

    public function getAllVerified($userId) {
        return Property::query()
            ->with(['photos', 'governorate'])
            ->whereNotNull('verified_at')
            ->where('user_id', '!=', $userId)
            ->orderByDesc('id')
            ->get();
    }

    public function getUserProperties($id) {
        $properties = Property::query()
            ->where('user_id', $id)
            ->latest()
            ->get();

        return $properties;
    }

    public function getUserProperty($userId, $propertyId) {
        $property = Property::query()
            ->where('id', $propertyId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return $property;
    }

    public function create(array $data) {
        return Property::create($data);
    }

    public function findById($id) {
        return Property::with('photos', 'governorate')->findOrFail($id);
    }

    public function update($id, array $data) {
        $property = Property::findOrFail($id);

        $property->fill($data);

        if ($property->isDirty()) {
            $property->verified_at = null;
        }

        $property->save();

        return $property->fresh(['photos', 'governorate']);
    }

    public function delete($id) {
        return Property::findOrFail($id)->delete();
    }

    public function markAsVerified($id) {
        $property = Property::findOrFail($id);
        $property->forceFill(['verified_at' => now()])->save();

        return $property;
    }

    public function findLockedOrFail(int $propertyId): Property {
        /** @var Property $property */
        $property = Property::query()
            ->whereKey($propertyId)
            ->lockForUpdate()
            ->firstOrFail();

        return $property;
    }

    public function saveReviewStats(Property $property, int $reviewersNumber, float $overallReviews): void {
        $property->forceFill([
            'reviewers_number' => $reviewersNumber,
            'overall_reviews'  => $overallReviews,
        ])->save();
    }

    public function resetReviewStats(Property $property): void {
        $this->saveReviewStats($property, 0, 0.0);
    }

    public function getCurrentReviewStats(Property $property): array {
        return [
            'count' => (int) ($property->reviewers_number ?? 0),
            'avg'   => (float) ($property->overall_reviews ?? 0),
        ];
    }
}
