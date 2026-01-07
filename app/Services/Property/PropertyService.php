<?php

namespace App\Services\Property;

use App\Models\Property\Property;
use App\Models\Reservation\Reservation;
use App\Models\User\User;
use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PropertyService {
    private $propertyRepository;
    public function __construct(PropertyRepositoryInterface $propertyRepository) {
        $this->propertyRepository = $propertyRepository;
    }

    public function getAll(array $filters) {
        return $this->propertyRepository->getAll($filters);
    }

    public function getAllVerified(User $user, array $filters = []) {
        $normalized = $this->normalizeFilters($filters);

        return $this->propertyRepository->getVerifiedExcludingUser($user, $normalized);
    }

    public function find(int $propertyId) {
        return $this->propertyRepository->findById($propertyId);
    }

    public function create(User $user, array $data) {
        $data['user_id'] = $user->id;

        return $this->propertyRepository->create($data);
    }

    public function update(Property $property, array $data) {
        return $this->propertyRepository->update($property, $data);
    }

    public function delete(Property $property) {
        return $this->propertyRepository->delete($property);
    }

    public function verifyProperty(Property $property) {
        if ($this->isPropertyVerified($property)) {
            return;
        }

        if ($this->propertyRepository->markAsVerified($property)) {
            $property->owner->notify(new \App\Notifications\PushNotification(
                'Property published',
                "[$property->title] has been published successfully.",
                [
                    'type' => 'property_verified',
                    'property_id' => (string) $property->property_id
                ]
            ));
        }
    }

    public function isPropertyVerified(Property $property) {
        return $property->verified_at !== null;
    }

    public function userProperties(User $user) {
        return $this->propertyRepository->getUserProperties($user);
    }

    public function userProperty(User $user, Property $property) {
        return $this->propertyRepository->getUserProperty($user, $property);
    }

    public function addReviewStats(Property $property, array $reviewData): void {
        $rating = $this->ratingFrom($reviewData);

        DB::transaction(function () use ($property, $rating) {
            $locked = $this->propertyRepository->findLockedOrFail($property);

            ['count' => $count, 'avg' => $avg] = $this->propertyRepository->getCurrentReviewStats($locked);

            [$newCount, $newAvg] = $this->calcAdd($count, $avg, $rating);

            $this->propertyRepository->saveReviewStats($locked, $newCount, $newAvg);
        });
    }

    public function replaceReviewRating(Property $property, array $oldReviewData, array $newReviewData): void {
        $oldRating = $this->ratingFrom($oldReviewData);
        $newRating = $this->ratingFrom($newReviewData);

        DB::transaction(function () use ($property, $oldRating, $newRating) {
            $locked = $this->propertyRepository->findLockedOrFail($property);

            ['count' => $count, 'avg' => $avg] = $this->propertyRepository->getCurrentReviewStats($locked);

            if ($count <= 0) {
                $this->propertyRepository->resetReviewStats($locked);
                return;
            }

            $newAvg = $this->calcReplace($count, $avg, $oldRating, $newRating);

            $this->propertyRepository->saveReviewStats($locked, $count, $newAvg);
        });
    }

    public function removeReviewStats(Property $property, array $reviewData): void {
        $rating = $this->ratingFrom($reviewData);

        DB::transaction(function () use ($property, $rating) {
            $locked = $this->propertyRepository->findLockedOrFail($property);

            ['count' => $count, 'avg' => $avg] = $this->propertyRepository->getCurrentReviewStats($locked);

            if ($count <= 1) {
                $this->propertyRepository->resetReviewStats($locked);
                return;
            }

            [$newCount, $newAvg] = $this->calcRemove($count, $avg, $rating);

            $this->propertyRepository->saveReviewStats($locked, $newCount, $newAvg);
        });
    }

    private function calcAdd(int $count, float $avg, float $rating): array {
        $newCount = $count + 1;
        $newAvg   = (($avg * $count) + $rating) / $newCount;

        return [$newCount, $this->normalizeAvg($newAvg)];
    }

    private function calcReplace(int $count, float $avg, float $oldRating, float $newRating): float {
        $newAvg = (($avg * $count) - $oldRating + $newRating) / $count;

        return $this->normalizeAvg($newAvg);
    }

    private function calcRemove(int $count, float $avg, float $rating): array {
        $newCount = $count - 1;
        $newAvg   = (($avg * $count) - $rating) / $newCount;

        return [$newCount, $this->normalizeAvg($newAvg)];
    }

    private function ratingFrom(array $reviewData): float {
        if (!array_key_exists('rating', $reviewData)) {
            throw new \InvalidArgumentException("rating is required");
        }

        return $this->normalizeRating((float) $reviewData['rating']);
    }

    private function normalizeRating(float $rating): float {
        return max(0.0, min(5.0, $rating));
    }

    private function normalizeAvg(float $avg): float {
        // Keep avg inside the valid stars range even if upstream data is corrupted.
        $avg = max(0.0, min(5.0, $avg));
        return round($avg, 2);
    }

    private function normalizeFilters(array $filters): array {
        $out = [];

        // title
        if (filled($filters['title'] ?? null)) {
            $out['title'] = trim((string) $filters['title']);
        }

        // governorate_id
        if (filled($filters['governorate_id'] ?? null)) {
            $out['governorate_id'] = (int) $filters['governorate_id'];
        }

        // rent_range "20-40" -> rent_min, rent_max
        if (filled($filters['rent_range'] ?? null)) {
            [$min, $max] = $this->parseRange((string) $filters['rent_range']);

            if ($min !== null && $max !== null && $min > $max) {
                [$min, $max] = [$max, $min];
            }

            if ($min !== null) $out['rent_min'] = $min;
            if ($max !== null) $out['rent_max'] = $max;
        }

        // per_page allowlist
        $allowedPerPage = [10, 15];
        $perPage = (int) ($filters['per_page'] ?? 15);
        $out['per_page'] = in_array($perPage, $allowedPerPage, true) ? $perPage : 15;

        // sort_by allowlist
        $allowedSortBy = ['id', 'rent', 'overall_reviews', 'verified_at', 'reviewers_number'];
        $sortBy = (string) ($filters['sort_by'] ?? 'id');
        $out['sort_by'] = in_array($sortBy, $allowedSortBy, true) ? $sortBy : 'id';

        // sort_dir allowlist
        $sortDir = strtolower((string) ($filters['sort_dir'] ?? 'desc'));
        $out['sort_dir'] = in_array($sortDir, ['asc', 'desc'], true) ? $sortDir : 'desc';

        return $out;
    }

    private function parseRange(string $range): array {
        $parts = preg_split('/\s*-\s*/', trim($range), 2);

        $min = $parts[0] ?? null;
        $max = $parts[1] ?? null;

        $min = is_numeric($min) ? (float) $min : null;
        $max = is_numeric($max) ? (float) $max : null;

        return [$min, $max];
    }
}
