<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UsersController extends Controller {
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function index(Request $request) {
        $filters = $request->only([
            'q',
            'verification',
            'activation',
            'role',
            'per_page'
        ]);

        $users = $this->userService->index($filters);

        return view('users.index', compact('users'));
    }

    public function verify(User $user) {
        $this->userService->verifyUser($user);

        return redirect()
            ->back()
            ->with('success', 'User verified successfully.');
    }

    public function toggleActivation(User $user) {
        $wasActive = is_null($user->deactivated_at);

        $this->userService->toggleAccount($user);

        $user->refresh();

        if ($wasActive && !is_null($user->deactivated_at)) {
            $user->tokens()->delete();

            $user->notify(new \App\Notifications\PushNotification(
                'Account blocked',
                "Your phone number [{$user->phone_number}] has been deactivated, please contact Support.",
                ['type' => 'account_deactivated']
            ));

            $user->devices()->delete();
        }

        return redirect()->back();
    }
}
