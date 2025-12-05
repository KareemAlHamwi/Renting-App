<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider {
    public function register(): void {
        $this->app->bind(
            \App\Repositories\Contracts\PersonRepositoryInterface::class,
            \App\Repositories\Eloquent\PersonRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );
    }

    public function boot(): void {
        JsonResource::withoutWrapping();
    }
}
