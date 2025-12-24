<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class UserMeResource extends JsonResource {
    public function toArray($request): array {
        $this->loadMissing('person');

        return [
            'id'           => $this->id,
            'username'     => $this->username,
            'phone_number' => $this->phone_number,
            'verified'     => $this->verified_at !== null,
            'verified_at'  => $this->verified_at ? (string) $this->verified_at : null,

            'person' => $this->person ? [
                'id'            => $this->person->id,
                'first_name'    => $this->person->first_name,
                'last_name'     => $this->person->last_name,
                'birthdate'     => $this->person->birthdate,
                'personal_photo' => $this->photoUrl($this->person->personal_photo),
                'id_photo'       => $this->photoUrl($this->person->id_photo),
            ] : null,
        ];
    }

    private function photoUrl(?string $path): ?string {
        if (!$path) return null;
        if (str_contains($path, '://')) return $path;

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($path);
    }
}
