<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller {
    public function create() {
        if (!Auth::check())
            return view('auth.login');

        return view('home');
    }

    public function show(User $user) {
        return view('users.show', ['user' => $user]);
    }

    public function destroy() {
        Auth::logout();

        return redirect('/login');
    }
}
