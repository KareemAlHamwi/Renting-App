<?php

namespace App\Repositories\Contracts\Property;

interface PropertyRepositoryInterface {
    public function getAll();
    public function create(array $data);
    public function findById($id);
    public function update($id, array $data);
    public function delete($id);
    public function markAsVerified($id);
    public function is_verified($id);
}
