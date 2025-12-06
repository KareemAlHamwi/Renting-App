<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource {
    public function toArray($request): array {
        $data = [
            'id'           => $this->id,
            'username'     => $this->username,
        ];

        // Include token only if the request wants it
        if ($request->get('include_token', false) && isset($this->access_token)) {
            $data['token'] = $this->access_token;
        }

        return $data;
    }
}
