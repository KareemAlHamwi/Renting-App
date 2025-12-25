<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\PropertyPhotoRequest;
use App\Services\Property\PropertyPhotoService;
use App\Models\Property\Property;
use App\Models\Property\PropertyPhoto;

class PropertyPhotoController extends Controller {
    protected PropertyPhotoService $propertyPhotoService;

    public function __construct(PropertyPhotoService $propertyPhotoService) {
        $this->propertyPhotoService = $propertyPhotoService;
    }

    public function store(PropertyPhotoRequest $request, int $propertyId) {
        $property = Property::findOrFail($propertyId);
        $this->authorize('update', $property);

        return $this->propertyPhotoService->createForProperty($propertyId, $request->all());
    }

    public function destroy(int $propertyId, int $id) {
        $photo = PropertyPhoto::where('property_id', $propertyId)->findOrFail($id);
        $this->authorize('update', $photo->property);

        $this->propertyPhotoService->deletePhoto($photo);

        return response()->noContent();
    }
}
