<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PropertyPhotoRepositoryInterface;
use App\Models\PropertyPhoto;

class PropertyPhotoRepository implements PropertyPhotoRepositoryInterface {
    public function create(array $data) {
        return PropertyPhoto::create($data);
    }

    public function delete($id) {
        PropertyPhoto::findOrFail($id)->delete();
    }
}
