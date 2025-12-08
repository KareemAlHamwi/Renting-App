<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\PersonService;
use App\Services\UserService;

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

        // Create token
        $token = $user->createToken($request->device_id)->plainTextToken;

        // Set token in user object for Resource
        $user->access_token = $token;

        // Return user with token
        return (new UserResource($user))->additional([
            'meta' => ['message' => 'Registration successful']
        ])->response()->setStatusCode(201);
    }

    public function login(LoginRequest $request) {
        $user = $this->userService->validateLogin(
            $request->phone_number,
            $request->password
        );

        $token = $user->createToken($request->device_id)->plainTextToken;
        $user->access_token = $token;

        return (new UserResource($user))->additional([
            'meta' => ['message' => 'Login successful']
        ])->response()->setStatusCode(200);
    }

    public function logout(LogoutRequest $request) {
        $user = $this->userService->findUserByPhone($request->phone_number);

        if ($user) {
            $user->tokens()->delete();
        }

        return response()->noContent();
    }
}
