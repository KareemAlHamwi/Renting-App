<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Property\Property;
use App\Models\Reservation\Review;
use App\Policies\Property\PropertyPolicy;
use App\Policies\Reservation\ReviewPolicy;

class AuthServiceProvider extends ServiceProvider {
    protected $policies = [
        Property::class => PropertyPolicy::class,
        Review::class => ReviewPolicy::class,
    ];

    public function boot(): void {
        $this->registerPolicies();
    }
}
