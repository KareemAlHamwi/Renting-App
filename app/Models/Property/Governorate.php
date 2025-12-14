<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model {


    protected $fillable = ['governorate_name'];

    public function properties() {
        return $this->hasMany(Property::class);
    }
}
