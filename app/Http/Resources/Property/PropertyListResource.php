<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyListResource extends JsonResource {
    public function toArray(Request $request): array {
        $this->loadMissing(['governorate', 'primaryPhoto']);

        $primary = $this->primaryPhoto;

        return [
            'id' => $this->id,
            'title' => $this->title,

            // minimal governorate info
            'governorate_name' => $this->governorate?->governorate_name,
            'governorate_id' => $this->governorate?->id,

            'rent' => $this->rent,

            'overall_reviews' => $this->overall_reviews,
            'reviewers_number' => (int) ($this->reviewers_number ?? 0),

            'primary_photo' => $primary ? [
                'path' => $primary->path,
                'url' => $this->photoUrl($primary->path),
            ] : null,
        ];
    }

    private function photoUrl(?string $path): ?string {
        if (!$path) return null;

        // If already absolute, keep it.
        if (str_contains($path, '://')) return $path;

        // Normalize "public/..." and leading slashes
        $path = preg_replace('#^public/#', '', $path);
        $path = ltrim($path, '/');

        // Return a RELATIVE public URL (no domain).
        $relative = str_starts_with($path, 'storage/')
            ? '/' . $path
            : '/storage/' . $path;

        // Prevent accidental "//"
        return preg_replace('#/+#', '/', $relative);
    }
}
