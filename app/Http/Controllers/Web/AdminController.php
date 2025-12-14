<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller {
    public function create() {
        if (Auth::check())
            return view('home');

        return view('auth.login');
    }

    public function store() {
        $credentials = request()->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'username' => 'Credentials do not match.'
            ]);
        }

        request()->session()->regenerate();

        if (Auth::user()->role !== 1) {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => 'Not authorized to access the admin panel.'
            ]);
        }

        return redirect('/');
    }
}
