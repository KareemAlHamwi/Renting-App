<?php

namespace App\Models\User;

use App\Models\Property\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    /** @use HasFactory<\Database\Factories\User\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * Keep this list minimal. Do NOT include role/verified_at unless you have a controlled admin-only flow.
     *
     * @var list<string>
     */
    protected $fillable = [
        'phone_number',
        'username',
        'password',
        'person_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    public $timestamps = false;

    protected $casts = [
        'verified_at' => 'date',
        'role'        => 'boolean',
        'password'    => 'hashed',
    ];

    public function person() {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function favorites() {
        return $this->belongsToMany(Property::class, 'favorites')->withTimestamps();
    }
}
