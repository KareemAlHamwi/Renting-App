<?php

namespace App\Models\Reservation;

use App\Models\Property\Property;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
    protected $fillable = [
        'start_date',
        'end_date',
        'status',
        'user_id',
        'property_id',
        'review_id'
    ];

    public function property() {
        return $this->belongsTo(Property::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function review() {
        return $this->belongsTo(Review::class);
    }
}
