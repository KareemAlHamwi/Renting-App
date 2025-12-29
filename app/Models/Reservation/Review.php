<?php

namespace App\Models\Reservation;

use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    protected $fillable = [
        'reservation_id',
        'stars',
        'review',
    ];

    protected $casts = [
        'stars' => 'float',
    ];

    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }
}
