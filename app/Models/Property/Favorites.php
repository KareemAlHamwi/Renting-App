<?php

namespace App\Models\Property;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Favorites extends Model {
    protected $fillable = ['user_id', 'property_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function property() {
        return $this->belongsTo(Property::class);
    }
}
