<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property\Property;
use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use App\Services\Property\PropertyService;

class PropertyController extends Controller {
    private PropertyRepositoryInterface $propertyRepository;
    private PropertyService $propertyService;

    public function __construct(PropertyRepositoryInterface $propertyRepository, PropertyService $propertyService) {
        $this->propertyRepository = $propertyRepository;
        $this->propertyService = $propertyService;
    }

    public function index() {
        $properties = $this->propertyRepository->getAll();
        return view('properties.index', compact('properties'));
    }

    public function verify(Property $property) {
        $this->propertyService->verifyProperty($property);

        return redirect()
            ->back()
            ->with('success', 'User verified successfully.');
    }
}
