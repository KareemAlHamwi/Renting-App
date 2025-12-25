<?php

namespace App\Services\Property;

use App\Repositories\Contracts\Property\GovernorateRepositoryInterface;

class GovernorateService {
    protected $governorateRepository;

    public function __construct(GovernorateRepositoryInterface $governorateRepository) {
        $this->governorateRepository = $governorateRepository;
    }

    public function getAll() {
        return $this->governorateRepository->getAll();
    }

    public function findById($id) {
        return $this->governorateRepository->findById($id);
    }
}
