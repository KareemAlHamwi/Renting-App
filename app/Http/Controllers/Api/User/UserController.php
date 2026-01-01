<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\ChangePhoneRequest;
use App\Http\Requests\User\DeleteAccountRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\User\UserMeResource;
use App\Http\Resources\User\UserPublicResource;
use App\Services\User\PersonService;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller {
    private PersonService $personService;
    private UserService $userService;

    public function __construct(PersonService $personService, UserService $userService) {
        $this->personService = $personService;
        $this->userService   = $userService;
    }

    public function myProfile(Request $request) {
        return new UserMeResource($request->user());
    }

    public function publicProfileByUsername(string $username) {
        return new UserPublicResource($this->userService->findUserByUsername($username));
    }

    public function update(UpdateProfileRequest $request) {
        $user = $request->user();

        $this->personService->updateForUser($user, $request->only([
            'first_name',
            'last_name',
            'birthdate',
            'personal_photo',
            'id_photo',
        ]));

        $this->userService->updateSelf($user, [
            'username' => $request->username,
        ]);

        return new UserMeResource($user->refresh()->load('person'));
    }

    public function changePhoneNumber(ChangePhoneRequest $request) {
        $user = $request->user();

        $this->userService->changeSelfPhone($user, $request->phone_number);

        return response()->json([
            'phone_number' => $request->phone_number
        ], 200);
    }

    public function changePassword(ChangePasswordRequest $request) {
        $user = $request->user();

        $this->userService->changeSelfPassword($user, $request->old_password, $request->new_password);

        return response()->json([
            'message' => 'Password updated successfully'
        ], 200);
    }

    public function deleteAccount(DeleteAccountRequest $request) {
        $user = $request->user();

        $this->userService->deleteSelf($user, $request->password);

        $this->personService->deleteForUser($user);

        return response()->noContent();
    }
}
