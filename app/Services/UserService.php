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

    public function allUsers() {
        return $this->users->index();
    }

    public function findUserById(string $phone) {
        return $this->users->show($phone);
    }

    public function findUserByPhone(string $phone) {
        return $this->users->showByPhone($phone);
    }

    public function findUserByUsername(string $phone) {
        return $this->users->showByUsername($phone);
    }

    public function createUser(array $data) {
        $data['password'] = Hash::make($data['password']);

        return $this->users->store($data);
    }

    public function updateUser(int $id, array $data) {
        $filteredData = array_filter($data, fn($value) => !is_null($value));

        $user = $this->users->update($id, $filteredData);

        return $user;
    }

    public function changeUserPhone($id, string $phone) {
        return $this->users->updatePhone($id, $phone);
    }

    public function changeUserPassword($id, string $password) {
        $hashedPassword = Hash::make($password);

        return $this->users->updatePassword($id, $hashedPassword);
    }

    public function deleteUser($id, $password) {
        $user = $this->findUserById($id);

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Password is incorrect.']
            ]);
        }

        $user->tokens()->delete();
        $user->delete();

        return $user;
    }

    public function validateLogin(string $phone, string $password) {
        $user = $this->findUserByPhone($phone);

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'phone_number' => ['The provided credentials are incorrect.']
            ]);
        }

        return $user;
    }
}
