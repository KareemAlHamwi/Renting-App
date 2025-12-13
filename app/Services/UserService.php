<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class UserService {
    private UserRepositoryInterface $users;

    public function __construct(UserRepositoryInterface $users) {
        $this->users = $users;
    }

    private function validateCredentials($user, string $password) {
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.']
            ]);
        }
    }

    public function allUsers() {
        return $this->users->index();
    }

    public function findUserById(int $id) {
        return $this->users->show($id);
    }

    public function findUserByPhone(string $phone) {
        return $this->users->showByPhone($phone);
    }

    public function findUserByUsername(string $username) {
        return $this->users->showByUsername($username);
    }

    public function createUser(array $data) {
        $data['password'] = Hash::make($data['password']);

        return $this->users->store($data);
    }

    public function updateUser(int $id, array $data) {
        $filteredData = array_filter($data, fn($value) => !is_null($value));

        return $this->users->update($id, $filteredData);
    }

    public function changeUserPhone(int $id, string $phone) {
        return $this->users->updatePhone($id, $phone);
    }

    public function changeUserPassword(int $id, string $old_password, string $new_password) {
        $user = $this->findUserById($id);

        if ($old_password === $new_password) {
            throw ValidationException::withMessages([
                'password' => ['Cannot use the same password.']
            ]);
        }

        $this->validateCredentials($user, $old_password);

        $hashedPassword = Hash::make($new_password);
        return $this->users->updatePassword($id, $hashedPassword);
    }

    public function deleteUser(int $id, string $password) {
        $user = $this->findUserById($id);

        $this->validateCredentials($user, $password);

        $user->tokens()->delete();
        $user->delete();

        return $user;
    }

    public function verifyUser(User $user): void {
        if ($this->isUserVerified($user)) {
            return;
        }

        $this->users->markAsVerified($user->id);
    }

    public function isUserVerified(User $user): bool {
        return $user->verified_at !== null;
    }


    public function validateLogin(string $identifier, string $password) {
        if (preg_match('/^(09[3-9]\d{7}|095\d{7}|944\d{7})$/', $identifier))
            $user = $this->findUserByPhone($identifier);
        else
            $user = $this->findUserByUsername($identifier);


        $this->validateCredentials($user, $password);

        return $user;
    }
}
