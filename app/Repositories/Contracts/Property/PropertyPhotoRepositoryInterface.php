<?php

namespace App\Repositories\Contracts\Property;

interface PropertyPhotoRepositoryInterface {
    public function create(array $data);
    public function delete($id);
}
