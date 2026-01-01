<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use App\Models\Property\Governorate;
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

    public function findById(Governorate $governorate) {
        return $this->governorateService->findById($governorate);
    }
}
