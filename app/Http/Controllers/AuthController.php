<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class AuthController extends Controller {
    private PersonRepositoryInterface $personRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        PersonRepositoryInterface $personRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->personRepository = $personRepository;
        $this->userRepository   = $userRepository;
    }

    public function store(Request $request) {
        $person = $this->personRepository->create($request->only([
            'first_name',
            'last_name',
            'birthdate',
            'personl_photo',
            'id_photo'
        ]));

        $user = $this->userRepository->create([
            'phone_number' => $request->phone_number,
            'username'    => $request->username,
            'password'    => $request->password,
            'person_id'    => $person->person_id,
        ]);

        return response()->json(['user' => $user]);
    }
}
