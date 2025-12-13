<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PropertyRepositoryInterface;
use App\Models\Property;

class PropertyRepository implements PropertyRepositoryInterface {
    public function getAll() {
        return Property::with('photos', 'governorate')->get();
    }

    public function create(array $data) {
        return Property::create($data);
    }

    public function findById($id) {
        return Property::with('photos', 'governorate')->findOrFail($id);
    }

    public function update($id, array $data) {
        $property = Property::findOrFail($id);
        $property->update($data);
        return $property;
    }

    public function delete($id) {
        Property::findOrFail($id)->delete();
    }
}
