<?php

namespace App\Repositories\Contracts;

interface PersonRepositoryInterface {
    public function create($request);
    public function findById($id);
}
