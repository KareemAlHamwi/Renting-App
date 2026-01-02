<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property\Property;
use App\Models\Reservation\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Models\User\User;

class DashboardController extends Controller {
    public function create() {
        if (!Auth::check())
            return view('auth.login');

        // Reservations
        $pendingReservations  = Reservation::where('status', \App\Enums\ReservationStatus::Pending)->count();
        $approvedReservations = Reservation::where('status', \App\Enums\ReservationStatus::Reserved)->count();
        $cancelledReservations = Reservation::where('status', \App\Enums\ReservationStatus::Cancelled)->count();

        $recentReservations = Reservation::with(['property', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Reviews
        $avgRating = (float) (\App\Models\Reservation\Review::avg('rating') ?? 0);

        // Attention items
        $propertiesNoPhotos = Property::whereDoesntHave('photos')->count();
        $unverifiedProperties = Property::whereNull('verified_at')->count();

        return view('home', [
            // Users
            'totalUsers'     => User::count(),
            'verifiedUsers'  => User::whereNotNull('verified_at')->count(),
            'pendingUsers'   => User::whereNull('verified_at')->count(),
            'adminsCount'    => User::where('role', 1)->count(),
            'latestUsers'    => User::join('people', 'people.id', '=', 'users.person_id')
                ->orderBy('people.created_at', 'desc')
                ->select('users.*')
                ->with('person')
                ->take(5)
                ->get(),

            // Properties
            'totalProperties'     => Property::count(),
            'verifiedProperties'  => Property::whereNotNull('verified_at')->count(),
            'pendingProperties'   => Property::whereNull('verified_at')->count(),
            'propertiesNoPhotos'  => $propertiesNoPhotos,

            // Reservations
            'pendingReservations'   => $pendingReservations,
            'approvedReservations'  => $approvedReservations,
            'cancelledReservations' => $cancelledReservations,
            'recentReservations'    => $recentReservations,

            // Reviews/Rent
            'avgRent'   => (float) (Property::avg('rent') ?? 0),
            'avgRating' => $avgRating,

            // Needs attention
            'attention' => [
                'pending_users'       => User::whereNull('verified_at')->count(),
                'unverified_properties' => $unverifiedProperties,
                'properties_no_photos' => $propertiesNoPhotos,
                'pending_reservations' => $pendingReservations,
            ],
        ]);
    }

    public function showUser(User $user) {
        return view('users.show', ['user' => $user]);
    }

    public function showProperty(Property $property) {
        return view('properties.show', ['property' => $property]);
    }

    public function showReservation(Reservation $reservation) {
        return view('reservations.show', ['reservation' => $reservation]);
    }

    public function destroy() {
        Auth::logout();

        return redirect('/login');
    }
}
