<?php

namespace App\Services\Property;

use App\Models\Property\Governorate;
use App\Repositories\Contracts\Property\GovernorateRepositoryInterface;

class GovernorateService {
    protected $governorateRepository;

    public function __construct(GovernorateRepositoryInterface $governorateRepository) {
        $this->governorateRepository = $governorateRepository;
    }

    public function getAll() {
        return $this->governorateRepository->getAll();
    }

    public function findById(Governorate $governorate) {
        return $this->governorateRepository->findById($governorate);
    }
}
