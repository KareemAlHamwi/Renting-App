<?php

namespace App\Services\Push;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class FcmClient {
    public function __construct(
        private FcmAccessTokenProvider $tokenProvider
    ) {
    }

    public function sendToToken(string $fcmToken, string $title, string $body, array $data = []): Response {
        $projectId = config('fcm.project_id');
        if (!$projectId) {
            throw new RuntimeException('Missing FIREBASE_PROJECT_ID.');
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        // FCM v1 payload structure :contentReference[oaicite:5]{index=5}
        $payload = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data' => $this->stringifyData($data),
            ],
        ];

        $accessToken = $this->tokenProvider->getAccessToken();

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->asJson()
            ->post($url, $payload);

        // If token expired/invalid, retry once by refreshing cached OAuth token.
        if ($response->status() === 401) {
            $this->tokenProvider->forget();

            $accessToken = $this->tokenProvider->getAccessToken();
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->asJson()
                ->post($url, $payload);
        }

        return $response;
    }

    private function stringifyData(array $data): array {
        $out = [];

        foreach ($data as $k => $v) {
            if (is_string($v)) {
                $out[(string) $k] = $v;
                continue;
            }

            $json = json_encode($v, JSON_UNESCAPED_UNICODE);
            $out[(string) $k] = $json !== false ? $json : (string) $v;
        }

        return $out;
    }
}
