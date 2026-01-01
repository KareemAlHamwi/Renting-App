<?php

namespace App\Repositories\Eloquent\Property;

use App\Repositories\Contracts\Property\GovernorateRepositoryInterface;
use App\Models\Property\Governorate;

class GovernorateRepository implements GovernorateRepositoryInterface {

    public function getAll() {
        return Governorate::all();
    }

    public function findById(Governorate $governorate) {
        return Governorate::findOrFail($governorate->id);
    }
}
