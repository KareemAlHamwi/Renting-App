<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User\User;
use App\Policies\User\UserPolicy;
use App\Models\Property\Property;
use App\Policies\Property\PropertyPolicy;
use App\Models\Reservation\Reservation;
use App\Policies\Reservation\ReservationPolicy;
use App\Models\Reservation\Review;
use App\Policies\Reservation\ReviewPolicy;

class AuthServiceProvider extends ServiceProvider {
    protected $policies = [
        User::class => UserPolicy::class,
        Property::class => PropertyPolicy::class,
        Review::class => ReviewPolicy::class,
        Reservation::class => ReservationPolicy::class
    ];

    public function boot(): void {
        $this->registerPolicies();
    }
}
