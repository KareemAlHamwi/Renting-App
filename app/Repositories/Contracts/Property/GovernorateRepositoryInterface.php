<?php

namespace App\Repositories\Contracts\Property;

interface GovernorateRepositoryInterface {
    public function getAll();
    public function findById($id);
}
