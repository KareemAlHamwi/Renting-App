<?php

namespace App\Repositories\Contracts;

interface PropertyPhotoRepositoryInterface {
    public function create(array $data);
    public function delete($id);
}
