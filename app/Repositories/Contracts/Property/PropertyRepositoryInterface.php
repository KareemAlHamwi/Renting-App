<?php

namespace App\Repositories\Contracts\Property;

use App\Models\Property\Property;
use App\Models\User\User;

interface PropertyRepositoryInterface {
    public function getAll(array $filters);
    public function getUserProperties(User $user);
    public function getUserProperty(User $user,Property $property);
    public function getVerifiedExcludingUser(User $user,array $filters);
    public function create(array $data);
    public function findById(int $propertyId);
    public function update(Property $property, array $data);
    public function delete(Property $property);
    public function markAsVerified(Property $property);
    public function findLockedOrFail(Property $property);
    public function saveReviewStats(Property $property, int $reviewersNumber, float $overallReviews);
    public function resetReviewStats(Property $property);
    public function getCurrentReviewStats(Property $property);
}
