<?php

namespace App\Providers;

use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use PersonRepository;
use UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {
    /**
     * Register services.
     */
    public function register(): void {
        $this->app->bind(
            PersonRepositoryInterface::class,
            PersonRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        //
    }
}
