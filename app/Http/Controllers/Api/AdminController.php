<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AdminResource;
use App\Services\AdminService;

class AdminController extends Controller {
    private AdminService $adminService;

    public function __construct(
        AdminService $adminService
    ) {
        $this->adminService = $adminService;
    }

    public function login(LoginRequest $request) {
        $admin = $this->adminService->validateLogin(
            $request->username,
            $request->password
        );

        $token = $admin->createToken($request->device_id)->plainTextToken;
        $admin->access_token = $token;

        return (new AdminResource($admin))->additional([
            'meta' => ['message' => 'Login successful']
        ])->response()->setStatusCode(200);
    }

    public function logout($request) {
        $admin = $this->adminService->checkUsername($request->username);

        if ($admin) {
            $admin->tokens()->delete();
        }

        return response()->noContent();
    }
}
