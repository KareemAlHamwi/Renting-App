<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Model;

class PropertyPhoto extends Model {
    public $timestamps = false;

    protected $fillable = [
        'property_id',
        'path',
        'order'
    ];

    public function property() {
        return $this->belongsTo(Property::class);
    }
}
