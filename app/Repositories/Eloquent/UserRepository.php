<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface {
    public function create($data) {
        return User::create($data);
    }

    public function findBy($phone) {
        return User::where('phone_number', $phone)->first();
    }
}
