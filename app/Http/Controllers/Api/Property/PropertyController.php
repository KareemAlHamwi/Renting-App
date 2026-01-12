<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\PropertyRequest;
use App\Http\Resources\Property\PropertyResource;
use App\Http\Resources\Property\PropertyListResource;
use App\Http\Resources\Property\UserPropertyListResource;
use App\Http\Resources\Property\UserPropertyResource;
use App\Services\Property\PropertyService;
use App\Models\Property\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller {
    private PropertyService $propertyService;

    public function __construct(PropertyService $propertyService) {
        $this->propertyService = $propertyService;
    }

    public function index(Request $request) {
        $properties = $this->propertyService->getAllVerified(
            $request->user(),
            $request->only([
                'title',
                'governorate_id',
                'rent_range',
                'per_page',
                'sort_by',
                'sort_dir',
            ])
        );

        return PropertyListResource::collection($properties);
    }

    public function show($property) {
        $property = $this->propertyService->find($property);
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

    public function toggle(Property $property) {
        $this->authorize('toggle', $property);

        $result = $this->propertyService->toggleProperty($property);

        return $result;
    }

    public function userProperties(Request $request) {
        $user = $request->user();

        $properties = $this->propertyService->userProperties($user);

        return UserPropertyListResource::collection($properties);
    }

    public function userProperty(Request $request, Property $property) {
        $user = $request->user();

        $property = $this->propertyService->userProperty($user, $property);

        return new UserPropertyResource($property);
    }
}
