<?php

namespace App\Repositories\Contracts\Property;

interface PropertyRepositoryInterface {
    public function getAll();
    public function getUserProperties($id);
    public function getUserProperty($userId, $propertyId);
    public function getAllVerified();
    public function create(array $data);
    public function findById($id);
    public function update($id, array $data);
    public function delete($id);
    public function markAsVerified($id);
}
