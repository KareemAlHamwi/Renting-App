<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model {
    use HasFactory;

    public $timestamps = true;

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
