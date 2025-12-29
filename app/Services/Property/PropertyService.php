<?php

namespace App\Services\Property;

use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use App\Models\User\User;
use App\Models\Property\Property;
use Illuminate\Support\Facades\DB;

class PropertyService {
    protected $propertyRepository;

    public function __construct(PropertyRepositoryInterface $propertyRepository) {
        $this->propertyRepository = $propertyRepository;
    }

    public function getAll() {
        return $this->propertyRepository->getAll();
    }

    public function getAllVerified(User $user) {
        return $this->propertyRepository->getAllVerified($user->id);
    }

    public function find($id) {
        return $this->propertyRepository->findById($id);
    }

    public function create(User $user, array $data) {
        $data['user_id'] = $user->id;

        return $this->propertyRepository->create($data);
    }

    public function update(Property $property, array $data) {
        return $this->propertyRepository->update($property->id, $data);
    }

    public function delete(Property $property) {
        return $this->propertyRepository->delete($property->id);
    }

    public function verifyProperty(Property $property): void {
        if ($this->isPropertyVerified($property)) {
            return;
        }

        $this->propertyRepository->markAsVerified($property->id);
    }

    public function isPropertyVerified(Property $property): bool {
        return $property->verified_at !== null;
    }

    public function userProperties(User $user) {
        return $this->propertyRepository->getUserProperties($user->id);
    }

    public function userProperty(User $user, $propertyId) {
        return $this->propertyRepository->getUserProperty($user->id, $propertyId);
    }

    public function addReviewStats(int $propertyId, array $reviewData): void {
        $rating = $this->ratingFrom($reviewData);

        DB::transaction(function () use ($propertyId, $rating) {
            $property = $this->propertyRepository->findLockedOrFail($propertyId);

            ['count' => $count, 'avg' => $avg] = $this->propertyRepository->getCurrentReviewStats($property);

            [$newCount, $newAvg] = $this->calcAdd($count, $avg, $rating);

            $this->propertyRepository->saveReviewStats($property, $newCount, $newAvg);
        });
    }

    public function replaceReviewRating(int $propertyId, array $oldReviewData, array $newReviewData): void {
        $oldRating = $this->ratingFrom($oldReviewData);
        $newRating = $this->ratingFrom($newReviewData);

        DB::transaction(function () use ($propertyId, $oldRating, $newRating) {
            $property = $this->propertyRepository->findLockedOrFail($propertyId);

            ['count' => $count, 'avg' => $avg] = $this->propertyRepository->getCurrentReviewStats($property);

            if ($count <= 0) {
                $this->propertyRepository->resetReviewStats($property);
                return;
            }

            $newAvg = $this->calcReplace($count, $avg, $oldRating, $newRating);

            // count doesn't change on replace
            $this->propertyRepository->saveReviewStats($property, $count, $newAvg);
        });
    }

    public function removeReviewStats(int $propertyId, array $reviewData): void {
        $rating = $this->ratingFrom($reviewData);

        DB::transaction(function () use ($propertyId, $rating) {
            $property = $this->propertyRepository->findLockedOrFail($propertyId);

            ['count' => $count, 'avg' => $avg] = $this->propertyRepository->getCurrentReviewStats($property);

            if ($count <= 1) {
                $this->propertyRepository->resetReviewStats($property);
                return;
            }

            [$newCount, $newAvg] = $this->calcRemove($count, $avg, $rating);

            $this->propertyRepository->saveReviewStats($property, $newCount, $newAvg);
        });
    }

    // -----------------------
    // Pure business logic
    // -----------------------

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

    private function normalizeAvg(float $avg): float {
        return round(max(0, $avg), 2);
    }

    private function ratingFrom(array $reviewData): float {
        return (float) ($reviewData['stars'] ?? 0);
    }
}
