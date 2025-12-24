<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource {
    public function toArray(Request $request): array {
        $this->loadMissing(['governorate', 'photos', 'owner']);

        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'governorate_id' => $this->governorate_id,
            'address'       => $this->address,
            'rent'          => $this->rent,
            'verified_at'   => $this->verified_at,

            'owner' => $this->whenLoaded('owner', function () {
                return [
                    'id'       => $this->owner?->id,
                    'username' => $this->owner?->username,
                ];
            }),

            'photos' => $this->whenLoaded('photos', function () {
                $sorted = $this->photos->sortBy('order')->values();

                return $sorted->map(function ($p) {
                    return [
                        'id'    => $p->id,
                        'order' => $p->order,
                        'path'  => $p->path,
                        'url'   => $this->photoUrl($p->path),
                    ];
                });
            }),

            'primary_photo' => $this->whenLoaded('photos', function () {
                $first = $this->photos->sortBy('order')->first();
                return $first ? $this->photoUrl($first->path) : null;
            }),
        ];
    }

    private function photoUrl(?string $path): ?string {
        if (!$path) return null;
        if (str_contains($path, '://')) return $path;

        $path = preg_replace('#^public/#', '', $path);
        $path = ltrim($path, '/');

        return str_starts_with($path, 'storage/')
            ? asset($path)
            : asset('storage/' . $path);
    }
}
