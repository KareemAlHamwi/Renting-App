<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    public function toArray($request): array {
        $data = [
            'id'           => $this->id,
            'username'     => $this->username,
            'phone_number' => $this->phone_number,
            'person'       => [
                'first_name'     => $this->person->first_name,
                'last_name'      => $this->person->last_name,
                // 'birthdate'      => $this->person->birthdate,
                'personal_photo' => $this->person->personal_photo,
                // 'id_photo'       => $this->person->id_photo,
            ],
        ];

        // Include token only if the request wants it
        if ($request->get('include_token', false) && isset($this->access_token)) {
            $data['token'] = $this->access_token;
        }

        return $data;
    }
}
