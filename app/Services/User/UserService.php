<?php

namespace App\Services\User;

use App\Models\User\User;
use App\Repositories\Contracts\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService {
    private UserRepositoryInterface $users;

    public function __construct(UserRepositoryInterface $users) {
        $this->users = $users;
    }

    private function validateCredentials(?User $user, string $password): void {
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.']
            ]);
        }
    }

    public function findUserByPhone(string $phone): ?User {
        return $this->users->showByPhone($phone);
    }

    public function findUserByUsername(string $username): ?User {
        return $this->users->showByUsername($username);
    }

    public function createUser(array $data): User {
        $data['password'] = Hash::make($data['password']);
        return $this->users->store($data);
    }

    public function updateSelf(User $user, array $data): User {
        $filteredData = array_filter($data, fn($value) => !is_null($value));
        return $this->users->update($user->id, $filteredData);
    }

    public function changeSelfPhone(User $user, string $phone): User {
        return $this->users->updatePhone($user->id, $phone);
    }

    public function changeSelfPassword(User $user, string $oldPassword, string $newPassword): User {
        if ($oldPassword === $newPassword) {
            throw ValidationException::withMessages([
                'password' => ['Cannot use the same password.']
            ]);
        }

        $this->validateCredentials($user, $oldPassword);

        return $this->users->updatePassword($user->id, Hash::make($newPassword));
    }

    public function deleteSelf(User $user, string $password): void {
        $this->validateCredentials($user, $password);

        $user->tokens()->delete();
        $this->users->destroy($user->id);
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

    public function validateLogin(string $identifier, string $password): User {
        if (preg_match('/^(09[3-9]\d{7}|095\d{7}|944\d{7})$/', $identifier)) {
            $user = $this->findUserByPhone($identifier);
        } else {
            $user = $this->findUserByUsername($identifier);
        }

        $this->validateCredentials($user, $password);

        return $user;
    }
}
