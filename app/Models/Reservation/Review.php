<?php

namespace App\Models\Reservation;

use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    protected $fillable = [
        'reservation_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'float',
    ];

    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }
}
