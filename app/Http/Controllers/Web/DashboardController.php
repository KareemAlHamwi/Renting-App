<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Property\Property;
use Illuminate\Support\Facades\Auth;
use App\Models\User\User;

class DashboardController extends Controller {
    public function create() {
        if (!Auth::check())
            return view('auth.login');

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

            'totalProperties'     => Property::count(),
            'verifiedProperties'  => Property::whereNotNull('verified_at')->count(),
            'pendingProperties'   => Property::whereNull('verified_at')->count(),

            'avgRent' => (float) (Property::avg('rent') ?? 0),
        ]);
    }

    public function showUser(User $user) {
        return view('users.show', ['user' => $user]);
    }

    public function showProperty(Property $property) {
        return view('properties.show', ['property' => $property]);
    }

    public function destroy() {
        Auth::logout();

        return redirect('/login');
    }
}
