<?php

namespace App\Services;

use App\Repositories\Contracts\GovernorateRepositoryInterface;

class GovernorateService {
    protected $repo;

    public function __construct(GovernorateRepositoryInterface $repo) {
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

    public function update($id, array $data) {
        return $this->repo->update($id, $data);
    }

    public function delete($id) {
        return $this->repo->delete($id);
    }
}
