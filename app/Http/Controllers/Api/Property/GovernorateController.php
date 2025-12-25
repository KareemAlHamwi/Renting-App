<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Property\GovernorateService;

class GovernorateController extends Controller {
    private GovernorateService $governorateService;

    public function __construct(GovernorateService $governorateService) {
        $this->governorateService = $governorateService;
    }

    public function index() {
        return $this->governorateService->getAll();
    }

    public function findById($id) {
        return $this->governorateService->findById($id);
    }
}
