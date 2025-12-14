<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Model;

class PropertyPhoto extends Model {


    protected $fillable = [
        'property_id',
        'path',
        'order'
    ];

    public function property() {
        return $this->belongsTo(Property::class);
    }
}
