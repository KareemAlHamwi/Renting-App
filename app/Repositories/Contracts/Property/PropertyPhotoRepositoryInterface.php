<?php

namespace App\Repositories\Contracts\Property;

use App\Models\Property\PropertyPhoto;

interface PropertyPhotoRepositoryInterface {
    public function create(array $data);
    public function delete(PropertyPhoto $propertyPhoto);
}
