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
    private PersonRepositoryInterface $personRepository;

    public function __construct(PersonRepositoryInterface $personRepository) {
        $this->personRepository = $personRepository;
    }

    public function createPerson(array $data) {
        $data = $this->hydratePhotoPathsForCreate($data);
        return $this->personRepository->store($data);
    }

    public function updateForUser(User $user, array $data) {
        $person = Person::query()->findOrFail($user->person_id);

        $data = $this->hydratePhotoPathsForUpdate($person, $data);

        return $this->personRepository->update($user->person_id, $data);
    }

    public function deleteForUser(User $user) {
        return $this->personRepository->destroy($user->person_id);
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

    private function hydratePhotoPathsForUpdate(Person $person, array $data): array {
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

        $maxBytes = 5 * 1024 * 1024;
        if (strlen($binary) > $maxBytes) {
            throw ValidationException::withMessages(['photo' => 'Image is too large.']);
        }

        $filename = Str::uuid()->toString() . '.' . $allowed[$mime];
        $path = trim($dir, '/') . '/' . $filename;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }
}
