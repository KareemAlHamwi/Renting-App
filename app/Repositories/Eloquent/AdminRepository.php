<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\AdminRepositoryInterface;
use App\Models\Admin;

class AdminRepository implements AdminRepositoryInterface {
    public function findByUsername($username) {
        return Admin::with('admin')->findOrFail($username,'username');
    }
}

