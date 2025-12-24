<?php

namespace App\Models\Reservation;

use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    protected $fillable = [
        'stars',
        'review'
    ];

    public function reservation() {
        return $this->hasOne(Reservation::class);
    }
}
