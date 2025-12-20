<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Property\PropertyService;

class PropertyController extends Controller {
    protected $service;

    public function __construct(PropertyService $service) {
        $this->service = $service;
    }

    public function index() {
        return $this->service->getAll();
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'governorate_id' => 'required|exists:governorates,id'
        ]);

        return $this->service->create($request->all());
    }

    public function show($id) {
        return $this->service->find($id);
    }

    public function update(Request $request, $id) {
        return $this->service->update($id, $request->all());
    }

    public function destroy($id) {
        return $this->service->delete($id);
    }
}
