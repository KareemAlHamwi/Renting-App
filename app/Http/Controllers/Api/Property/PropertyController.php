<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\PropertyRequest;
use App\Http\Resources\Property\PropertyResource;
use App\Services\Property\PropertyService;
use App\Models\Property\Property;

class PropertyController extends Controller {
    protected $service;

    public function __construct(PropertyService $service) {
        $this->service = $service;
    }

    public function index() {
        return $this->service->getAll();
    }

    public function store(PropertyRequest $request) {
        $this->authorize('create', Property::class);

        $property = $this->service->create(
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

    public function show($id) {
        return $this->service->find($id);
    }

    public function update(PropertyRequest $request, Property $property) {
        $this->authorize('update', $property);

        $property = $this->service->update(
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

        $this->service->delete($property);
        return response()->noContent();
    }
}
