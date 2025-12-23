<?php

namespace App\Services\Property;

use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use App\Models\User\User;
use App\Models\Property\Property;

class PropertyService {
    protected $repo;

    public function __construct(PropertyRepositoryInterface $repo) {
        $this->repo = $repo;
    }

    public function getAll() {
        return $this->repo->getAll();
    }

    public function find($id) {
        return $this->repo->findById($id);
    }

    public function create(User $user, array $data) {
        $data['user_id'] = $user->id;

        return $this->repo->create($data);
    }

    public function update(Property $property, array $data) {
        return $this->repo->update($property->id, $data);
    }

    public function delete(Property $property) {
        return $this->repo->delete($property->id);
    }

    public function verifyProperty(Property $property): void {
        if ($this->isPropertyVerified($property)) {
            return;
        }

        $this->repo->markAsVerified($property->id);
    }

    public function isPropertyVerified(Property $property): bool {
        return $property->verified_at !== null;
    }
}
