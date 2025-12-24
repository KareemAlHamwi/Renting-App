<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class UserPublicResource extends JsonResource {
    public function toArray($request): array {
        $this->loadMissing('person');

        return [
            'id'       => $this->id,
            'username' => $this->username,
            'phone_number' => $this->phone_number,
            'full name'    => $this->person->first_name . ' ' . $this->person->last_name,
            'avatar' => $this->photoUrl($this->person?->personal_photo),
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
