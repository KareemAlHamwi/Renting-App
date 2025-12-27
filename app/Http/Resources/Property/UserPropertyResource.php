<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPropertyResource extends JsonResource {
    public function toArray(Request $request): array {
        $this->loadMissing(['governorate', 'photos', 'owner']);

        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'description'    => $this->description,
            'address'        => $this->address,
            'rent'           => $this->rent,

            'overall_reviews'  => $this->overall_reviews,
            'reviewers_number' => (int) ($this->reviewers_number ?? 0),

            'verified_at'    => $this->verified_at,

            'governorate' => $this->whenLoaded('governorate', function () {
                return [
                    'id' => $this->governorate?->id,
                    'governorate_name' => $this->governorate?->governorate_name,
                ];
            }),

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
