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
use App\Models\User\User;
use Illuminate\Http\Request;

class PropertyController extends Controller {
    private PropertyService $propertyService;

    public function __construct(PropertyService $propertyService) {
        $this->propertyService = $propertyService;
    }

    public function index(Request $request) {
        $user = $request->user();

        $properties = $this->propertyService->getAllVerified($user);
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

    public function userProperties(Request $request) {
        $user = $request->user();

        $properties = $this->propertyService->userProperties($user);

        return UserPropertyListResource::collection($properties);
    }

    public function userProperty(Request $request, $propertyId) {
        $user = $request->user();

        $property = $this->propertyService->userProperty($user, $propertyId);

        return new UserPropertyResource($property);
    }
}
