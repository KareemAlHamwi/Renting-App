<?php

namespace App\Services\Property;

use App\Models\Property\Property;
use App\Models\Property\PropertyPhoto;
use App\Repositories\Contracts\Property\PropertyPhotoRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PropertyPhotoService {
    private $propertyPhotoRepository;
    public function __construct(PropertyPhotoRepositoryInterface $propertyPhotoRepository) {
        $this->propertyPhotoRepository = $propertyPhotoRepository;
    }

    public function createForProperty(Property $property, array $data): array {
        $photos = [];

        if (!empty($data['photos']) && is_array($data['photos'])) {
            $photos = $data['photos'];
        } else {
            $single = $data['photo'] ?? $data['path'] ?? $data['Path'] ?? null;
            if ($single) {
                $photos = [$single];
            }
        }

        if (count($photos) === 0) {
            throw ValidationException::withMessages([
                'photo' => 'photo/photos is required.',
            ]);
        }

        if (count($photos) > 5) {
            throw ValidationException::withMessages([
                'photos' => 'You can upload a maximum of 5 images per request.',
            ]);
        }

        $existingCount = PropertyPhoto::where('property_id', $property->id)->count();
        if (($existingCount + count($photos)) > 5) {
            $remaining = max(0, 5 - $existingCount);
            throw ValidationException::withMessages([
                'photos' => "This property already has {$existingCount} photo(s). You can upload only {$remaining} more (max 5 total).",
            ]);
        }

        $baseDir = "properties/{$property->id}";

        $currentMaxOrder = PropertyPhoto::where('property_id', $property->id)->max('order');
        $nextOrder = ($currentMaxOrder ?? 0) + 1;

        $created = [];

        $storedPaths = [];

        try {
            foreach ($photos as $b64) {
                $relativePath = $this->storeBase64Image($b64, $baseDir);
                $storedPaths[] = $relativePath;

                $order = null;
                if (count($photos) === 1 && !empty($data['order'])) {
                    $order = (int) $data['order'];
                } else {
                    $order = $nextOrder++;
                }

                $row = $this->propertyPhotoRepository->create([
                    'property_id' => $property->id,
                    'path'        => $relativePath,
                    'order'       => $order,
                ]);

                $photoModel = $row instanceof PropertyPhoto ? $row : PropertyPhoto::find($row->id ?? null);

                $created[] = [
                    'id'    => $photoModel?->id ?? null,
                    'order' => $order,
                    'path'  => $relativePath,
                    'url'   => $this->publicUrl($relativePath),
                ];
            }
        } catch (\Throwable $e) {
            foreach ($storedPaths as $path) {
                try {
                    $this->deleteStoredPath($path);
                } catch (\Throwable $inner) {
                    Log::warning('Failed to cleanup stored file after create failure', [
                        'path' => $path,
                        'error' => $inner->getMessage(),
                    ]);
                }
            }

            throw $e;
        }

        return ['photos' => $created];
    }

    public function deletePhoto(PropertyPhoto $propertyPhoto): void {
        $path = $propertyPhoto->path;

        $this->propertyPhotoRepository->delete($propertyPhoto);

        try {
            $this->deleteStoredPath($path);
        } catch (\Throwable $e) {
            Log::warning('Failed to delete property photo file from storage', [
                'property_photo_id' => $propertyPhoto->id,
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function publicUrl(?string $path): ?string {
        if (!$path) return null;
        if (str_contains($path, '://')) return $path;

        $relative = $this->normalizeStoragePath($path);
        if (!$relative) return null;

        return asset('storage/' . ltrim($relative, '/'));
    }

    private function deleteStoredPath(?string $path): void {
        $relative = $this->normalizeStoragePath($path);

        if (!$relative) {
            return;
        }

        Storage::disk('public')->delete($relative);
    }

    private function normalizeStoragePath(?string $path): ?string {
        if (!$path) return null;

        if (str_contains($path, '://')) {
            return null;
        }

        $path = ltrim($path, '/');

        $path = preg_replace('#^public/#', '', $path);
        $path = preg_replace('#^storage/#', '', $path);

        $path = ltrim($path, '/');

        return $path !== '' ? $path : null;
    }

    private function storeBase64Image(string $base64, string $dir): string {
        $data = $base64;

        if (preg_match('/^data:\s*image\/[a-zA-Z0-9.+-]+;\s*base64,/', $base64)) {
            $data = substr($base64, strpos($base64, ',') + 1);
        }

        $data = str_replace(' ', '+', $data);

        $binary = base64_decode($data, true);
        if ($binary === false) {
            throw ValidationException::withMessages(['photo' => 'Invalid base64 encoding.']);
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->buffer($binary);

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($allowed[$mime])) {
            throw ValidationException::withMessages(['photo' => 'Unsupported image type.']);
        }

        $maxBytes = 6 * 1024 * 1024;
        if (strlen($binary) > $maxBytes) {
            throw ValidationException::withMessages(['photo' => 'Image is too large.']);
        }

        $filename = Str::uuid()->toString() . '.' . $allowed[$mime];
        $path = trim($dir, '/') . '/' . $filename;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }
}
