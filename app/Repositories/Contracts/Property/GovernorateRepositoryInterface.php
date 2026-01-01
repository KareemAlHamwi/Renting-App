<?php

namespace App\Repositories\Contracts\Property;

use App\Models\Property\Governorate;

interface GovernorateRepositoryInterface {
    public function getAll();
    public function findById(Governorate $governorate);
}
