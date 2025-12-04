<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\PersonRepository;
use App\Repositories\Eloquent\UserRepository;

class AuthController extends Controller {
    private PersonRepository $personRepository;
    private UserRepository $userRepository;

    public function __construct(
        PersonRepository $personRepository,
        UserRepository $userRepository
    ) {
        $this->personRepository = $personRepository;
        $this->userRepository   = $userRepository;
    }

    public function store(Request $request) {
        $person = $this->personRepository->create($request->only([
            'first_name',
            'last_name',
            'birthdate',
            'personal_photo',
            'id_photo'
        ]));

        $user = $this->userRepository->create([
            'phone_number' => $request->phone_number,
            'username'    => $request->username,
            'password'    => bcrypt($request->password),
            'person_id'    => $person->id,
        ]);

        return response()->json(['user' => $user]);
    }

    public function find(Request $request) {
        $phone = $request->input('phone_number');
        $user = $this->userRepository->findByPhone($phone);

        return response()->json(['user' => $user]);
    }
}
