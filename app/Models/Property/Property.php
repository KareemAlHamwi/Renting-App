<?php

namespace App\Models\Property;

use App\Models\Reservation\Reservation;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model {
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'address',
        'rent',
        'overall_reviews',
        'reviewers_number',
        'published_at',
        'verified_at',
        'governorate_id',
        'user_id',
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function governorate() {
        return $this->belongsTo(Governorate::class);
    }

    public function photos() {
        return $this->hasMany(PropertyPhoto::class);
    }

    public function primaryPhoto() {
        return $this->hasOne(PropertyPhoto::class)
            ->orderBy('order');
    }

    public function favoritedBy() {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
}
