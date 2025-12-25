<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Repositories\Contracts\User\UserRepositoryInterface;
use App\Services\User\UserService;

class UsersController extends Controller {
    private UserRepositoryInterface $userRepository;
    private UserService $userService;

    public function __construct(UserRepositoryInterface $userRepository, UserService $userService) {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    public function index() {
        $users = $this->userRepository->index();
        return view('users.index', compact('users'));
    }

    public function verify(User $user) {
        $this->userService->verifyUser($user);

        return redirect()
            ->back()
            ->with('success', 'User verified successfully.');
    }
}
