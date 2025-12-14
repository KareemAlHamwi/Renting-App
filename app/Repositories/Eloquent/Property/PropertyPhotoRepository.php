<?php

namespace App\Repositories\Eloquent\Property;

use App\Repositories\Contracts\Property\PropertyPhotoRepositoryInterface;
use App\Models\Property\PropertyPhoto;

class PropertyPhotoRepository implements PropertyPhotoRepositoryInterface {
    public function create(array $data) {
        return PropertyPhoto::create($data);
    }

    public function delete($id) {
        PropertyPhoto::findOrFail($id)->delete();
    }
}
