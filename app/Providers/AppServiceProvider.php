<?php

namespace App\Providers;

use App\Repositories\Contracts\Property\FavoritesRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

// User
use App\Repositories\Contracts\User\PersonRepositoryInterface;
use App\Repositories\Contracts\User\UserRepositoryInterface;
use App\Repositories\Eloquent\User\PersonRepository;
use App\Repositories\Eloquent\User\UserRepository;

// Property
use App\Repositories\Contracts\Property\GovernorateRepositoryInterface;
use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use App\Repositories\Contracts\Property\PropertyPhotoRepositoryInterface;
use App\Repositories\Eloquent\Property\FavoriteRepository;
use App\Repositories\Eloquent\Property\GovernorateRepository;
use App\Repositories\Eloquent\Property\PropertyRepository;
use App\Repositories\Eloquent\Property\PropertyPhotoRepository;

class AppServiceProvider extends ServiceProvider {
    public function register(): void {
        // User
        $this->app->bind(
            PersonRepositoryInterface::class,
            PersonRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        // Property
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

        $this->app->bind(
            FavoritesRepositoryInterface::class,
            FavoriteRepository::class
        );
    }

    public function boot(): void {
        JsonResource::withoutWrapping();
    }
}
