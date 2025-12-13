<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PropertyPhotoService;

class PropertyPhotoController extends Controller {
    protected $service;

    public function __construct(PropertyPhotoService $service) {
        $this->service = $service;
    }

    public function store(Request $request) {
        $request->validate([
            'Path' => 'required|string',
            'property_id' => 'required|exists:properties,id'
        ]);

        return $this->service->create($request->all());
    }

    public function destroy($id) {
        return $this->service->delete($id);
    }
}
