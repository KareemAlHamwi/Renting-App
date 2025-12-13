<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GovernorateService;

class GovernorateController extends Controller {
    protected $service;

    public function __construct(GovernorateService $service) {
        $this->service = $service;
    }

    public function index() {
        return $this->service->getAll();
    }

    public function store(Request $request) {
        $request->validate([
            'GovernorateName' => 'required|string|max:255'
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
