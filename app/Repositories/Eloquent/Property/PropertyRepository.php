<?php

namespace App\Repositories\Eloquent\Property;

use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use App\Models\Property\Property;

class PropertyRepository implements PropertyRepositoryInterface {
    public function getAll() {
        return Property::with('photos', 'governorate')->orderByDesc('id')->get();
    }

    public function getAllVerified() {
        return Property::with('photos', 'governorate')->whereNotNull('verified_at')->orderByDesc('id')->get();
    }

    public function getUserProperties($id) {
        $properties = Property::query()
            ->where('user_id', $id)
            ->latest()
            ->get();

        return $properties;
    }

    public function getUserProperty($userId, $propertyId) {
        $property = Property::query()
            ->where('id', $propertyId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return $property;
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
}
