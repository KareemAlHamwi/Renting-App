<?php

namespace App\Http\Resources\Property;

use App\Support\Utilities;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyListResource extends JsonResource {
    public function toArray(Request $request): array {
        $this->loadMissing(['governorate', 'primaryPhoto']);

        $primary = $this->primaryPhoto;

        return [
            'id' => $this->id,
            'title' => $this->title,

            'governorate_name' => $this->governorate?->governorate_name,
            'governorate_id' => $this->governorate?->id,

            'rent' => $this->rent,

            'overall_reviews' => $this->overall_reviews,
            'reviewers_number' => (int) ($this->reviewers_number ?? 0),

            'primary_photo' => $primary ? [
                'path' => $primary->path,
                'url' => Utilities::photoUrl($primary->path),
            ] : null,
        ];
    }
}
