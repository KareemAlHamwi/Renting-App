<?php

namespace App\Services\Push;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class FcmAccessTokenProvider {
    public function getAccessToken(): string {
        return Cache::remember('fcm_access_token', now()->addMinutes(55), function () {
            $path = config('fcm.service_account_json');
            $scope = [config('fcm.scope')];

            if (!$path || !is_file($path)) {
                throw new RuntimeException('FCM service account JSON not found. Check FIREBASE_SERVICE_ACCOUNT_JSON.');
            }

            $creds = new ServiceAccountCredentials($scope, $path);
            $token = $creds->fetchAuthToken();

            if (!isset($token['access_token'])) {
                throw new RuntimeException('Failed to fetch FCM OAuth access token.');
            }

            return (string) $token['access_token'];
        });
    }

    public function forget(): void {
        Cache::forget('fcm_access_token');
    }
}
