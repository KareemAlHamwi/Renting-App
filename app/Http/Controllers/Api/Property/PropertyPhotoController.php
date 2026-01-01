<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\PropertyPhotoRequest;
use App\Models\Property\Property;
use App\Models\Property\PropertyPhoto;
use App\Services\Property\PropertyPhotoService;

class PropertyPhotoController extends Controller {
    private $propertyPhotoService;
    public function __construct(PropertyPhotoService $propertyPhotoService) {
        $this->propertyPhotoService = $propertyPhotoService;
    }

    public function store(PropertyPhotoRequest $request, Property $property) {
        $this->authorize('update',$property);

        $result = $this->propertyPhotoService->createForProperty(
            $property,
            $request->validated()
        );

        return response()->json($result, 201);
    }

    public function destroy(Property $property, PropertyPhoto $propertyPhoto) {
        if ($propertyPhoto->property_id !== $property->getKey()) {
            abort(404);
        }

        $this->authorize('update', $property);

        $this->propertyPhotoService->deletePhoto($propertyPhoto);

        return response()->noContent();
    }
}
