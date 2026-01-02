<?php

namespace App\Services\Property;

use App\Models\Property\Property;
use App\Models\Reservation\Reservation;
use App\Models\User\User;
use App\Repositories\Contracts\Property\PropertyRepositoryInterface;

class PropertyService {
    private $propertyRepository;
    public function __construct(PropertyRepositoryInterface $propertyRepository) {
        $this->propertyRepository = $propertyRepository;
    }

    public function getAll() {
        return $this->propertyRepository->getAll();
    }

    public function getAllVerified(User $user) {
        return $this->propertyRepository->getAllVerified($user);
    }

    public function find(Property $property) {
        return $this->propertyRepository->findById($property);
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

        $this->propertyRepository->markAsVerified($property);
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

    public function addReviewStats(Reservation $reservation, array $reviewData) {
        $property = $reservation->relationLoaded('property')
            ? $reservation->property
            : $reservation->load('property')->property;

        $rating = $this->ratingFrom($reviewData);

        $this->propertyRepository->applyReviewStatsAdd($property, $rating);
    }

    public function replaceReviewRating(Property $property, array $oldReviewData, array $newReviewData) {
        $oldRating = $this->ratingFrom($oldReviewData);
        $newRating = $this->ratingFrom($newReviewData);

        $this->propertyRepository->applyReviewStatsReplace($property, $oldRating, $newRating);
    }

    public function removeReviewStats(Property $property, array $reviewData) {
        $rating = $this->ratingFrom($reviewData);

        $this->propertyRepository->applyReviewStatsRemove($property, $rating);
    }

    private function ratingFrom(array $reviewData) {
        return (float) ($reviewData['rating'] ?? 0);
    }
}
