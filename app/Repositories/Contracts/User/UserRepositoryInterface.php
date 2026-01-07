<?php

namespace App\Repositories\Contracts\User;

use App\Models\User\User;

interface UserRepositoryInterface {
    public function index(array $filters);
    public function findByPhone(string $phone);
    public function findByUsername(string $username);
    public function store(array $data);
    public function update(User $user, array $data);
    public function updatePhone(User $user, string $phone);
    public function updatePassword(User $user, string $password);
    public function markAsVerified(User $user);
    public function destroy(User $user);
    public function isVerified(User $user);
}
