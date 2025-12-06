<?php

namespace App\Services;

use App\Repositories\Contracts\AdminRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminService {
    private AdminRepositoryInterface $admin;

    public function __construct(AdminRepositoryInterface $admin) {
        $this->admin = $admin;
    }

    public function checkUsername($username) {
        return $this->admin->findByUsername($username);
    }

    public function validateLogin(string $username, string $password) {
        $user = $this->checkUsername($username);

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.']
            ]);
        }

        return $user;
    }
}
