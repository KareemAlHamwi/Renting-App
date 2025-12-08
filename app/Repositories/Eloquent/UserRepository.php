<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface {
    public function index() {
        return User::all();
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

    public function destroy($id) {
        return User::findOrFail($id)->delete();
    }
}
