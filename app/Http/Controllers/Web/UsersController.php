<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Repositories\Contracts\User\UserRepositoryInterface;
use App\Services\User\UserService;

class UsersController extends Controller {
    private UserRepositoryInterface $users;
    private UserService $userService;

    public function __construct(UserRepositoryInterface $users, UserService $userService) {
        $this->users = $users;
        $this->userService = $userService;
    }

    public function index() {
        $users = $this->users->index();
        return view('users.index', compact('users'));
    }

    public function verify(User $user) {
        $this->userService->verifyUser($user);

        return redirect()
            ->back()
            ->with('success', 'User verified successfully.');
    }
}
