<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Person extends Model {
    use HasFactory ,HasApiTokens;
    protected $fillable = [
        'first_name',
        'last_name',
        'birthdate',
        'personal_photo',
        'id_photo'
    ];

    public function user() {
        return $this->hasOne(User::class);
    }
}
