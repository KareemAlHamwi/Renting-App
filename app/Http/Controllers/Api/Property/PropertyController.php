<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\PropertyRequest;
use App\Http\Resources\Property\PropertyResource;
use App\Http\Resources\Property\PropertyListResource;
use App\Services\Property\PropertyService;
use App\Models\Property\Property;

class PropertyController extends Controller {
    private PropertyService $propertyService;

    public function __construct(PropertyService $propertyService) {
        $this->propertyService = $propertyService;
    }

    public function index() {
        $properties = $this->propertyService->getAllVerified();
        return PropertyListResource::collection($properties);
    }

    public function show($id) {
        $property = $this->propertyService->find($id);
        return new PropertyResource($property);
    }

    public function store(PropertyRequest $request) {
        $this->authorize('create', Property::class);

        $property = $this->propertyService->create(
            $request->user(),
            $request->only([
                'title',
                'description',
                'governorate_id',
                'address',
                'rent',
            ])
        );

        return new PropertyResource($property);
    }

    public function update(PropertyRequest $request, Property $property) {
        $this->authorize('update', $property);

        $property = $this->propertyService->update(
            $property,
            $request->only([
                'title',
                'description',
                'governorate_id',
                'address',
                'rent'
            ])
        );

        return new PropertyResource($property);
    }

    public function destroy(Property $property) {
        $this->authorize('delete', $property);

        $this->propertyService->delete($property);
        return response()->noContent();
    }
}
