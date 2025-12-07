<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
    public function create() {
        if (!Auth::check())
            return view('auth.login');

        return view('dashboard');
    }

    public function destroy() {
        Auth::logout();

        return redirect('/login');
    }
}
