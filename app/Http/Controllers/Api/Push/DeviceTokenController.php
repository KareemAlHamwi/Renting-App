<?php

namespace App\Http\Controllers\Api\Push;

use App\Http\Controllers\Controller;
use App\Http\Requests\Push\UpsertDeviceTokenRequest;
use App\Models\User\UserDevice;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller {
    public function upsert(UpsertDeviceTokenRequest $request) {
        $user = $request->user();

        UserDevice::updateOrCreate(
            [
                'user_id'   => $user->id,
                'device_id' => $request->device_id,
            ],
            [
                'platform'     => $request->platform,
                'fcm_token'    => $request->fcm_token,
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'meta' => ['message' => 'FCM token saved'],
        ]);
    }

    public function delete(Request $request) {
        $request->validate([
            'device_id' => ['required', 'string', 'max:255'],
        ]);

        $request->user()
            ->devices()
            ->where('device_id', $request->device_id)
            ->delete();

        return response()->noContent();
    }
}
