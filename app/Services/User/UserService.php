<?php

namespace App\Services\User;

use App\Models\User\User;
use App\Repositories\Contracts\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService {
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $users) {
        $this->userRepository = $users;
    }

    public function index(array $filters) {
        return $this->userRepository->index($filters);
    }

    public function findByPhone(string $phone): ?User {
        return $this->userRepository->findByPhone($phone);
    }

    public function findByUsername(string $username): ?User {
        return $this->userRepository->findByUsername($username);
    }

    public function createUser(array $data): User {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->store($data);
    }

    public function updateSelf(User $user, array $data): User {
        $filteredData = array_filter($data, fn($value) => !is_null($value));
        return $this->userRepository->update($user, $filteredData);
    }

    public function changeSelfPhone(User $user, string $phone): User {
        return $this->userRepository->updatePhone($user, $phone);
    }

    public function changeSelfPassword(User $user, string $oldPassword, string $newPassword): User {
        if ($oldPassword === $newPassword) {
            throw ValidationException::withMessages([
                'password' => ['Cannot use the same password.']
            ]);
        }

        $this->validateCredentials($user, $oldPassword);

        return $this->userRepository->updatePassword($user, Hash::make($newPassword));
    }

    public function deleteSelf(User $user, string $password): void {
        $this->validateCredentials($user, $password);

        $user->tokens()->delete();
        $this->userRepository->destroy($user);
    }

    public function toggleAccount(User $user) {
        if ($this->userRepository->isActivated($user)) {
            $this->userRepository->deactivate($user);
            return ['message' => 'User has been deactivated',];
        }

        $this->userRepository->activate($user);
        return ['message' => 'User has been activated',];
    }

    public function verifyUser(User $user): void {
        if ($this->isUserVerified($user)) {
            return;
        }

        if ($this->userRepository->markAsVerified($user)) {
            $user->notify(new \App\Notifications\PushNotification(
                'Account verified',
                "Your account [$user->username] has been verified successfully.",
                ['type' => 'account_verified']
            ));
        }
    }

    public function isUserVerified(User $user): bool {
        return $user->verified_at !== null;
    }

    public function validateLogin(string $identifier, string $password): User {
        if ($this->isPhoneFormat($identifier)) {
            $user = $this->findByPhone($identifier);
        } else {
            $user = $this->findByUsername($identifier);
        }

        $this->validateCredentials($user, $password);

        return $user;
    }

    public function isVerified(User $user): bool {
        return $this->userRepository->isVerified($user);
    }

    private function isPhoneFormat(string $identifier): bool {
        return preg_match('/^(09[3-9]\d{7}|095\d{7}|944\d{7})$/', $identifier);
    }

    private function validateCredentials(?User $user, string $password): void {
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.']
            ]);
        }
    }
}
