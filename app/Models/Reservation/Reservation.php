<?php

namespace App\Models\Reservation;

use App\Models\Property\Property;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ReservationStatus;

class Reservation extends Model {
    protected $fillable = [
        'start_date',
        'end_date',
        'status',
        'user_id',
        'property_id',
    ];

    protected $casts = [
        'status' => ReservationStatus::class,
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function property() {
        return $this->belongsTo(Property::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function review() {
        return $this->hasOne(Review::class);
    }
}
