<?php

namespace App\Policies\User;

use App\Models\User\User;
use App\Services\User\UserService;
use Illuminate\Auth\Access\AuthorizationException;

class UserPolicy {
    private UserService $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function login(?User $user, string $identifier): bool {
        $user = $this->isPhoneFormat($identifier)
            ? $this->userService->findByPhone($identifier)
            : $this->userService->findByUsername($identifier);

        if ($this->isPhoneDeactivated($user)) {
            throw new AuthorizationException('This phone number has been deactivated.');
        }

        return true;
    }

    private function isPhoneFormat(string $identifier): bool {
        return preg_match('/^(09[3-9]\d{7}|095\d{7}|944\d{7})$/', $identifier);
    }

    private function isPhoneDeactivated(?User $user): bool {
        return $user !== null && !is_null($user->deactivated_at);
    }
}
