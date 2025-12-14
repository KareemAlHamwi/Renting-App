<?php

namespace App\Repositories\Eloquent\User;

use App\Repositories\Contracts\User\UserRepositoryInterface;
use App\Models\User\User;

class UserRepository implements UserRepositoryInterface {
    public function index() {
        $users = User::with('person')
            ->orderBy('id', 'desc')
            ->get();

        return view('users.index', compact('users'));
    }

    public function show($id) {
        return User::where('id', $id)->first();
    }

    public function showByPhone($phone) {
        return User::where('phone_number', $phone)->first();
    }

    public function showByUsername($phone) {
        return User::where('username', $phone)->first();
    }

    public function store(array $data) {
        return User::create($data);
    }

    public function update($id, array $data) {
        $user = User::findOrFail($id);
        $user->update($data);

        return $user;
    }

    public function updatePhone($id, $phone) {
        $user = User::findOrFail($id);
        $user->update(['phone_number' => $phone]);

        return $user;
    }

    public function updatePassword($id, $password) {
        $user = User::findOrFail($id);
        $user->update(['password' => $password]);

        return $user;
    }

    public function markAsVerified($id) {
    User::where('id', $id)->update([
        'verified_at' => now()
    ]);
}

    public function is_verified($id) {
        $user = User::findOrFail($id);

        return $user->verified_at != null;
    }

    public function destroy($id) {
        return User::findOrFail($id)->delete();
    }
}
