<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model {


    protected $fillable = ['GovernorateName'];

    public function properties() {
        return $this->hasMany(Property::class);
    }
}
