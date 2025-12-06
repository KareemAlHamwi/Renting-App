<?php

namespace App\Repositories\Contracts;

interface AdminRepositoryInterface {
    public function findByUsername($username);
}
