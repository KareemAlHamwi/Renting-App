<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

// User
use App\Repositories\Contracts\User\PersonRepositoryInterface;
use App\Repositories\Contracts\User\UserRepositoryInterface;
use App\Repositories\Eloquent\User\PersonRepository;
use App\Repositories\Eloquent\User\UserRepository;

// Property
use App\Repositories\Contracts\Property\FavoriteRepositoryInterface;
use App\Repositories\Contracts\Property\GovernorateRepositoryInterface;
use App\Repositories\Contracts\Property\PropertyPhotoRepositoryInterface;
use App\Repositories\Contracts\Property\PropertyRepositoryInterface;
use App\Repositories\Eloquent\Property\FavoriteRepository;
use App\Repositories\Eloquent\Property\GovernorateRepository;
use App\Repositories\Eloquent\Property\PropertyPhotoRepository;
use App\Repositories\Eloquent\Property\PropertyRepository;

// Reservation
use App\Repositories\Contracts\Reservation\ReservationRepositoryInterface;
use App\Repositories\Contracts\Reservation\ReviewRepositoryInterface;
use App\Repositories\Eloquent\Reservation\ReservationRepository;
use App\Repositories\Eloquent\Reservation\ReviewRepository;

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
            FavoriteRepositoryInterface::class,
            FavoriteRepository::class
        );

        // Reservation
        $this->app->bind(
            ReservationRepositoryInterface::class,
            ReservationRepository::class
        );
        $this->app->bind(
            ReviewRepositoryInterface::class,
            ReviewRepository::class
        );
    }

    public function boot(): void {
        JsonResource::withoutWrapping();
    }
}
