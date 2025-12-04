<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface {
    public function create($request);
    public function findByPhone($id);
}
