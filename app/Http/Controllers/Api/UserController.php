<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\PersonService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller {
    private PersonService $personService;
    private UserService $userService;

    public function __construct(
        PersonService $personService,
        UserService $userService
    ) {
        $this->personService = $personService;
        $this->userService   = $userService;
    }

    public function index() {
        return $this->userService->allUsers();
    }


    public function show(Request $request) {
        $user = $request->user();

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request) {
        $person = $this->personService->updatePerson(
            $request->id,
            $request->only([
                'first_name',
                'last_name',
                'birthdate',
                'personal_photo',
                'id_photo'
            ])
        );

        $user = $this->userService->updateUser(
            $request->id,
            [
                'username'     => $request->username,
                'person_id'    => $person->id
            ]
        );

        return new UserResource($user);
    }

    public function changePhoneNumber(UpdateUserRequest $request) {
        $this->userService->changeUserPhone(
            $request->id,
            $request->phone_number
        );

        return response()->json([
            'phone_number' => $request->phone_number
        ], 200);
    }

    public function changePassword(UpdateUserRequest $request) {
        $this->userService->changeUserPassword(
            $request->id,
            $request->old_password,
            $request->new_password
        );

        return response()->json([
            'message' => 'Password updated successfully'
        ], 200);
    }

    public function destroy(UpdateUserRequest $request) {
        $user = $this->userService->deleteUser(
            $request->id,
            $request->password
        );

        if ($user) {
            $this->personService->deletePerson($request->id);
        }

        return response()->json(null, 204);
    }

    public function verify(User $user) {
        $this->userService->verifyUser($user);

        return redirect()
            ->back()
            ->with('success', 'User verified successfully.');
    }
}
