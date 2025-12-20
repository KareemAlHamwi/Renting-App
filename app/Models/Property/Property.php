<?php

namespace App\Models\Property;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Property extends Model {



    protected $fillable = [
        'title',
        'description',
        'address',
        'rent',
        'overall_reviews',
        'reviewers_number',
        'verified_at',
        'governorate_id',
        'user_id'
    ];

    public function governorate() {
        return $this->belongsTo(Governorate::class);
    }

    public function photos() {
        return $this->hasMany(PropertyPhoto::class);
    }
    public function favoritedBy() {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}
