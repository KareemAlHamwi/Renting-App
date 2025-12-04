<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider {
    public function boot(): void {
        JsonResource::withoutWrapping();
    }

    public function register(): void {
    }
}
