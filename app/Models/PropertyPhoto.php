<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyPhoto extends Model {


    protected $fillable = [
        'Path',
        'Order',
        'property_id'

    ];

    public function property() {
        return $this->belongsTo(Property::class);
    }
}
