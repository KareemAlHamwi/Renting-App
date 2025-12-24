<?php

namespace App\Services\User;

use App\Models\User\User;
use App\Models\User\Person;
use App\Repositories\Contracts\User\PersonRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class PersonService {
    private PersonRepositoryInterface $people;

    public function __construct(PersonRepositoryInterface $people) {
        $this->people = $people;
    }

    public function allPeople() {
        return $this->people->index();
    }

    public function findPersonById(int $id) {
        return $this->people->show($id);
    }

    public function createPerson(array $data) {
        $data = $this->hydratePhotoPathsForCreate($data);
        return $this->people->store($data);
    }


    public function updateForUser(User $user, array $data) {
        $person = Person::query()->findOrFail($user->person_id);

        $data = $this->hydratePhotoPathsForUpdate($person, $data);

        return $this->people->update($user->person_id, $data);
    }

    public function deleteForUser(User $user) {
        return $this->people->destroy($user->person_id);
    }

    private function hydratePhotoPathsForCreate(array $data): array {
        if (isset($data['personal_photo'])) {
            $data['personal_photo'] = $this->storePhotoInput($data['personal_photo'], 'users/personal_photos');
        }

        if (isset($data['id_photo'])) {
            $data['id_photo'] = $this->storePhotoInput($data['id_photo'], 'users/id_photos');
        }

        return $data;
    }

    private function hydratePhotoPathsForUpdate(\App\Models\User\Person $person, array $data): array {
        if (array_key_exists('personal_photo', $data)) {
            $data['personal_photo'] = $this->replacePhotoInput($person->personal_photo, $data['personal_photo'], 'users/personal_photos');
        }

        if (array_key_exists('id_photo', $data)) {
            $data['id_photo'] = $this->replacePhotoInput($person->id_photo, $data['id_photo'], 'users/id_photos');
        }

        return $data;
    }

    private function storePhotoInput(UploadedFile|string $input, string $dir): string {
        if ($input instanceof UploadedFile) {
            return $input->store($dir, 'public');
        }

        if (is_string($input)) {
            return $this->storeBase64Image($input, $dir);
        }

        throw ValidationException::withMessages(['photo' => 'Invalid photo input.']);
    }

    private function replacePhotoInput(?string $oldPath, UploadedFile|string $input, string $dir): string {
        if ($oldPath && !str_contains($oldPath, '://')) {
            Storage::disk('public')->delete($oldPath);
        }

        return $this->storePhotoInput($input, $dir);
    }

    private function storeBase64Image(string $base64, string $dir): string {
        // Accept both:
        // 1) data:image/png;base64,AAA...
        // 2) raw base64 AAA...

        $data = $base64;

        // If it's a data URI, strip header and keep the base64 part
        if (preg_match('/^data:\s*image\/[a-zA-Z0-9.+-]+;\s*base64,/', $base64)) {
            $data = substr($base64, strpos($base64, ',') + 1);
        }

        // Some clients send spaces instead of '+'
        $data = str_replace(' ', '+', $data);

        $binary = base64_decode($data, true);
        if ($binary === false) {
            throw ValidationException::withMessages(['photo' => 'Invalid base64 encoding.']);
        }

        // Detect MIME from binary (do not trust the client header)
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

        // Optional: cap size (bytes) to protect your server
        $maxBytes = 5 * 1024 * 1024; // 5MB
        if (strlen($binary) > $maxBytes) {
            throw ValidationException::withMessages(['photo' => 'Image is too large.']);
        }

        $filename = Str::uuid()->toString() . '.' . $allowed[$mime];
        $path = trim($dir, '/') . '/' . $filename;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }
}
