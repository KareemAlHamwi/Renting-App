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

    public function show($id) {
        return User::with('person')->find($id);
    }

    public function showByPhone($phone) {
        return User::with('person')
            ->where('phone_number', $phone)
            ->first();
    }

    public function showByUsername($username) {
        return User::with('person')
            ->where('username', $username)
            ->first();
    }

    public function store(array $data) {
        $user = User::create($data);
        return $user->load('person');
    }

    public function update($id, array $data) {
        $user = User::findOrFail($id);
        $user->update($data);

        return $user->load('person');
    }

    public function updatePhone($id, $phone) {
        $user = User::findOrFail($id);
        $user->update(['phone_number' => $phone]);

        return $user->load('person');
    }

    public function updatePassword($id, $password) {
        $user = User::findOrFail($id);
        $user->update(['password' => $password]);

        return $user->load('person');
    }

    public function markAsVerified($id) {
        $user = User::findOrFail($id);
        $user->forceFill(['verified_at' => now()])->save();

        return $user->load('person');
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();

        return true;
    }
}
