<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property\Property;
use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use App\Services\Property\PropertyService;

class PropertyController extends Controller {
    private PropertyRepositoryInterface $properties;
    private PropertyService $propertyService;

    public function __construct(PropertyRepositoryInterface $properties, PropertyService $propertyService) {
        $this->properties = $properties;
        $this->propertyService = $propertyService;
    }

    public function index() {
        $properties = $this->properties->getAll();
        return view('properties.index', compact('properties'));
    }

    public function verify(Property $property) {
        $this->propertyService->verifyProperty($property);

        return redirect()
            ->back()
            ->with('success', 'User verified successfully.');
    }
}
