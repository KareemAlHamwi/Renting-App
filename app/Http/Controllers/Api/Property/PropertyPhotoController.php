<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\PropertyPhotoRequest;
use App\Services\Property\PropertyPhotoService;
use App\Models\Property\Property;
use App\Models\Property\PropertyPhoto;

class PropertyPhotoController extends Controller {
    protected $service;

    public function __construct(PropertyPhotoService $service) {
        $this->service = $service;
    }

    public function store(PropertyPhotoRequest $request, int $propertyId) {
        $property = Property::findOrFail($propertyId);
        $this->authorize('update', $property);

        return $this->service->createForProperty($propertyId, $request->all());
    }

    public function destroy(int $propertyId, int $id) {
        $photo = PropertyPhoto::where('property_id', $propertyId)->findOrFail($id);
        $this->authorize('update', $photo->property);

        $this->service->deletePhoto($photo);

        return response()->noContent();
    }
}
