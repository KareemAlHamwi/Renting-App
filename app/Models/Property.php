<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model {



    protected $fillable = [
        'Title',
        'Description',
        'Address',
        'Rent',
        'OverAllReviews',
        'ReviewersNumber',
        'verified_at',
        'governorate_id'

    ];

    public function governorate() {
        return $this->belongsTo(Governorate::class);
    }

    public function photos() {
        return $this->hasMany(PropertyPhoto::class);
    }
}
