<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property\Property;
use App\Services\Property\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller {
    private PropertyService $propertyService;

    public function __construct(PropertyService $propertyService) {
        $this->propertyService = $propertyService;
    }

    // public function index() {
    //     $properties = $this->propertyService->getAll();
    //     return view('properties.index', compact('properties'));
    // }

    public function index(Request $request) {
        $filters = $request->only([
            'q',
            'governorate_id',
            'status',
            'publishment',
            'per_page',
            'sort_by',
            'sort_dir'
        ]);

        $properties = $this->propertyService->getAll($filters);

        return view('properties.index', compact('properties'));
    }


    public function verify(Property $property) {
        $this->propertyService->verifyProperty($property);

        return redirect()
            ->back()
            ->with('success', 'User verified successfully.');
    }

    public function togglePublishing(Property $property) {
        $this->propertyService->toggleProperty($property);

        if (!is_null($property->published_at)) {
            $property->owner->notify(new \App\Notifications\PushNotification(
                'Property published',
                "Your property [$property->title] has been published.",
                ['type' => 'property_published']
            ));
        } else {
            $property->owner->notify(new \App\Notifications\PushNotification(
                'Property unpublished',
                "Your property [$property->title] has been unpublished.",
                ['type' => 'property_published']
            ));
        }

        return redirect()
            ->back();
    }
}
