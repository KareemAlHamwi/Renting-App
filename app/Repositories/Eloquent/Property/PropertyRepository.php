<?php

namespace App\Repositories\Eloquent\Property;

use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use App\Models\Property\Property;

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
        return Property::findOrFail($id)->delete();
    }

    public function markAsVerified($id) {
        $property = Property::findOrFail($id);
        $property->forceFill(['verified_at' => now()])->save();

        return $property;
    }

    public function is_verified($id) {
        $user = Property::findOrFail($id);
        return $user->verified_at !== null;
    }
}
