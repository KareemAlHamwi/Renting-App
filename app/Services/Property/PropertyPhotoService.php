<?php

namespace App\Services\Property;

use App\Repositories\Contracts\Property\PropertyPhotoRepositoryInterface;

class PropertyPhotoService {
    protected $repo;

    public function __construct(PropertyPhotoRepositoryInterface $repo) {
        $this->repo = $repo;
    }

    public function create(array $data) {
        return $this->repo->create($data);
    }

    public function delete($id) {
        return $this->repo->delete($id);
    }
}
