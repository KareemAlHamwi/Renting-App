<?php

namespace App\Repositories\Contracts\Property;

use App\Models\Property\Property;

interface PropertyRepositoryInterface {
    public function getAll();
    public function getUserProperties($id);
    public function getUserProperty($userId, $propertyId);
    public function getAllVerified($userId);
    public function create(array $data);
    public function findById($id);
    public function update($id, array $data);
    public function delete($id);
    public function markAsVerified($id);
    public function findLockedOrFail(int $propertyId): Property;
    public function saveReviewStats(Property $property, int $reviewersNumber, float $overallReviews): void;
    public function resetReviewStats(Property $property): void;
    public function getCurrentReviewStats(Property $property): array;
}
