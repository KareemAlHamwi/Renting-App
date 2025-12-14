<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\UserResource;
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
            'id_photo'
        ]));

        $user = $this->userService->createUser([
            'phone_number' => $request->phone_number,
            'username'     => $request->username,
            'password'     => $request->password,
            'person_id'    => $person->id,
            'device_id'    => $request->device_id,
        ]);

        $token = $user->createToken($request->device_id)->plainTextToken;

        $user->access_token = $token;

        return (new UserResource($user))->additional([
            'meta' => ['message' => 'Registration successful']
        ])->response()->setStatusCode(201);
    }

    public function login(LoginRequest $request) {
        $identifier = $request->phone_number ?? $request->username;

        $user = $this->userService->validateLogin(
            $identifier,
            $request->password
        );

        $token = $user->createToken($request->device_id)->plainTextToken;
        $user->access_token = $token;

        return (new UserResource($user))->additional([
            'meta' => ['message' => 'Login successful']
        ])->response()->setStatusCode(200);
    }

    public function logout(Request $request) {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete();
        }

        return response()->noContent();
    }
}
