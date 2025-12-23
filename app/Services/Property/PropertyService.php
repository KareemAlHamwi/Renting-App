<?php

namespace App\Services\Property;

use App\Repositories\Contracts\Property\PropertyRepositoryInterface;

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

    public function create(array $data) {
        return $this->repo->create($data);
    }

    public function update($user, array $data) {
        return $this->repo->update($user, $data);
    }

    public function delete($id) {
        return $this->repo->delete($id);
    }
}
