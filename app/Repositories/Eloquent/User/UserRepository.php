<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User\User;
use App\Repositories\Contracts\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface {
    public function index() {
        return User::with('person')
            ->orderByDesc('id')
            ->get();
    }

    public function findByPhone(string $phone) {
        return User::with('person')
            ->where('phone_number', $phone)
            ->first();
    }

    public function findByUsername(string $username) {
        return User::with('person')
            ->where('username', $username)
            ->first();
    }

    public function store(array $data) {
        $user = User::create($data);
        return $user->load('person');
    }

    public function update(User $user, array $data) {
        $user = User::findOrFail($user->id);
        $user->update($data);

        return $user->load('person');
    }

    public function updatePhone(User $user, string $phone) {
        $user = User::findOrFail($user->id);

        $user->phone_number = $phone;

        if ($user->isDirty('phone_number')) {
            $user->verified_at = null;
        }

        $user->save();

        return $user->load('person');
    }

    public function updatePassword(User $user, string $password) {
        $user = User::findOrFail($user->id);
        $user->update(['password' => $password]);

        return $user->load('person');
    }

    public function markAsVerified(User $user) {
        $user = User::findOrFail($user->id);
        $user->forceFill(['verified_at' => now()])->save();

        return $user->load('person');
    }

    public function destroy(User $user) {
        $user = User::findOrFail($user->id);
        $user->delete();

        return true;
    }
}
