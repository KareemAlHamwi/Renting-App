<?php

namespace App\Notifications\Channels;

use App\Models\User\User;
use App\Models\User\UserDevice;
use App\Services\Push\FcmClient;
use App\Notifications\Contracts\FcmNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class FcmChannel {
    public function __construct(
        private FcmClient $client
    ) {
    }

    public function send(object $notifiable, Notification $notification): void {
        if (!$notifiable instanceof User) {
            return;
        }

        if (!$notification instanceof FcmNotification) {
            return;
        }

        $message = $notification->toFcm($notifiable);

        $devices = $notifiable->devices()->get();

        foreach ($devices as $device) {
            /** @var UserDevice $device */
            $response = $this->client->sendToToken(
                $device->fcm_token,
                $message->title,
                $message->body,
                $message->data
            );

            if (! $response->successful()) {
                Log::error('FCM send failed', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                    'device_id' => $device->device_id ?? null,
                    'user_id' => $notifiable->id ?? null,
                ]);
            } else {
                Log::info('FCM send success', [
                    'status' => $response->status(),
                    'body' => $response->json(), // should include "name"
                    'device_id' => $device->device_id ?? null,
                    'user_id' => $notifiable->id ?? null,
                ]);
            }

            if ($this->looksLikeUnregisteredToken($response->json())) {
                $device->delete();
            }
        }
    }

    private function looksLikeUnregisteredToken(?array $json): bool {
        if (!$json) return false;

        $details = $json['error']['details'] ?? [];
        foreach ($details as $d) {
            if (($d['errorCode'] ?? null) === 'UNREGISTERED') {
                return true;
            }
        }

        return false;
    }
}
