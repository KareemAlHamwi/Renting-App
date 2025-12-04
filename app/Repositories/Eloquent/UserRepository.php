<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface {
    public function create($data) {
        $data['password'] = bcrypt($data['password']);
        return User::create($data);
    }

    public function findByPhone($phone) {
        return User::where('phone_number', $phone)->first();
    }
}
