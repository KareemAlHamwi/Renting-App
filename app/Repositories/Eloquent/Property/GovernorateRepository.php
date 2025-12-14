<?php

namespace App\Repositories\Eloquent\Property;

use App\Repositories\Contracts\Property\GovernorateRepositoryInterface;
use App\Models\Property\Governorate;

class GovernorateRepository implements GovernorateRepositoryInterface {

    public function getAll() {
        return Governorate::all();
    }

    public function findById($id) {
        return Governorate::with('properties')->findOrFail($id);
    }

    public function create(array $data) {
        return Governorate::create($data);
    }

    public function update($id, array $data) {
        $gov = Governorate::findOrFail($id);
        $gov->update($data);
        return $gov;
    }

    public function delete($id) {
        Governorate::findOrFail($id)->delete();
    }
}
