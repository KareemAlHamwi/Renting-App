<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPublicResource extends JsonResource {
    public function toArray($request): array {
        $this->loadMissing('person');

        return [
            'id'       => $this->id,
            'username' => $this->username,
            'phone_number' => $this->phone_number,
            'full name'    => $this->person->first_name.' '.$this->person->last_name,
            'avatar'   => $this->person?->personal_photo,
        ];
    }
}
