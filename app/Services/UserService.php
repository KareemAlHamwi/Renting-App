<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService {
    private UserRepositoryInterface $users;

    public function __construct(UserRepositoryInterface $users) {
        $this->users = $users;
    }

    public function createUser(array $data) {
        $data['password'] = Hash::make($data['password']);

        return $this->users->create($data);
    }

    public function findByPhone(string $phone) {
        return $this->users->findBy($phone);
    }

    public function validateLogin(string $phone, string $password) {
        $user = $this->findByPhone($phone);

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'phone_number' => ['The provided credentials are incorrect.']
            ]);
        }

        return $user;
    }
}
