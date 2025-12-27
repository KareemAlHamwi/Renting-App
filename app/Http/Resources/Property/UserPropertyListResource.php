<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPropertyListResource extends JsonResource {
    public function toArray(Request $request): array {
        $this->loadMissing(['governorate', 'primaryPhoto']);

        $primary = $this->primaryPhoto;

        return [
            'id' => $this->id,
            'title' => $this->title,

            // minimal governorate info
            'governorate_name' => $this->governorate?->governorate_name,

            'rent' => $this->rent,

            'overall_reviews' => $this->overall_reviews,
            'reviewers_number' => (int) ($this->reviewers_number ?? 0),
            'verified_at' => $this->verified_at,

            // photo with order = 1 (or smallest order)
            'primary_photo' => $primary ? [
                'path' => $primary->path,
                'url' => $this->photoUrl($primary->path),
            ] : null,
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
