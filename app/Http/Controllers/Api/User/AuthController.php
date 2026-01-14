<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\UserMeResource;
use App\Models\User\User;
use App\Services\User\PersonService;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller {
    private PersonService $personService;
    private UserService $userService;

    public function __construct(
        PersonService $personService,
        UserService $userService
    ) {
        $this->personService = $personService;
        $this->userService   = $userService;
    }

    public function register(RegisterRequest $request) {
        $person = $this->personService->createPerson($request->only([
            'first_name',
            'last_name',
            'birthdate',
            'personal_photo',
            'id_photo',
        ]));

        $user = $this->userService->createUser([
            'phone_number' => $request->phone_number,
            'username'     => $request->username,
            'password'     => $request->password,
            'person_id'    => $person->id,
        ]);

        $token = $this->issueTokenForDevice($user, $request->device_id);

        return response()->json([
            'token' => $token,
            'user'  => new UserMeResource($user),
            'meta'  => ['message' => 'Registration successful'],
        ], 201);
    }

    public function login(LoginRequest $request) {
        $identifier = $request->phone_number ?? $request->username;
        $this->authorize('login', [User::class, $identifier]);

        $user = $this->userService->validateLogin($identifier, $request->password);

        $token = $this->issueTokenForDevice($user, $request->device_id);

        return response()->json([
            'token' => $token,
            'user'  => new UserMeResource($user),
            'meta'  => ['message' => 'Login successful'],
        ]);
    }

    public function logout(Request $request) {
        /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
        $token = $request->user()?->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->noContent();
    }

    public function logoutAll(Request $request) {
        $request->user()?->tokens()->delete();

        return response()->noContent();
    }

    private function issueTokenForDevice(
        User $user,
        string $deviceId
    ): string {
        $user->tokens()
            ->where('name', $deviceId)
            ->delete();

        return $user
            ->createToken($deviceId)
            ->plainTextToken;
    }

    public function checkSession(Request $request) {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'authenticated' => false,
                'message' => 'Invalid or missing token.',
            ], 401);
        }

        $token = $user->currentAccessToken();

        if (!$token) {
            return response()->json([
                'authenticated' => false,
                'message' => 'Token not found.',
            ], 401);
        }

        return response()->json([
            'message' => 'Token found.'
        ],200);
    }
}
