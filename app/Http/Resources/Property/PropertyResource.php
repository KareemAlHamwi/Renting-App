<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        $this->loadMissing(['governorate', 'photos', 'owner']);


        return [
            'title'       => $this->title,
            'description' => $this->description,
            'governorate_id' => $this->governorate_id,
            'address'    => $this->address,
            'rent'   => $this->rent,
        ];
    }
}
