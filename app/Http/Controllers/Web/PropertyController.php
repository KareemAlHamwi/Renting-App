<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property\Property;
use App\Services\Property\PropertyService;

class PropertyController extends Controller {
    private PropertyService $propertyService;

    public function __construct(PropertyService $propertyService) {
        $this->propertyService = $propertyService;
    }

    public function index() {
        $properties = $this->propertyService->getAll();
        return view('properties.index', compact('properties'));
    }

    public function verify(Property $property) {
        $this->propertyService->verifyProperty($property);

        return redirect()
            ->back()
            ->with('success', 'User verified successfully.');
    }
}
