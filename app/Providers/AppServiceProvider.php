<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Repositories\Eloquent\PropertyRepository;
use  App\Repositories\Contracts\PropertyRepositoryInterface;

use App\Repositories\Eloquent\PropertyPhotoRepository;
use  App\Repositories\Contracts\PropertyPhotoRepositoryInterface;

use App\Repositories\Eloquent\GovernorateRepository;
use  App\Repositories\Contracts\GovernorateRepositoryInterface;

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
        $this->app->bind(
            GovernorateRepositoryInterface::class,
            GovernorateRepository::class
        );

        $this->app->bind(
            PropertyRepositoryInterface::class,
            PropertyRepository::class
        );

        $this->app->bind(
            PropertyPhotoRepositoryInterface::class,
            PropertyPhotoRepository::class
        );
    }

    public function boot(): void {
        JsonResource::withoutWrapping();
    }
}
