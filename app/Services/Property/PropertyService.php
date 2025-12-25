<?php

namespace App\Services\Property;

use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use App\Models\User\User;
use App\Models\Property\Property;

class PropertyService {
    protected $propertyRepository;

    public function __construct(PropertyRepositoryInterface $propertyRepository) {
        $this->propertyRepository = $propertyRepository;
    }

    public function getAll() {
        return $this->propertyRepository->getAll();
    }

    public function getAllVerified() {
        return $this->propertyRepository->getAllVerified();
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
}
