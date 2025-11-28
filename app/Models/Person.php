<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model {
    protected $fillable = [
        'phone_number',
        'first_name',
        'last_name',
        'birthdate',
        'personal_photo',
        'id_photo'
    ];

    // One person can have one user account
    public function user() {
        return $this->hasOne(User::class);
    }
}
