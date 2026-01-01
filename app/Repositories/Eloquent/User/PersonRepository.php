<?php

namespace App\Repositories\Eloquent\User;

use App\Repositories\Contracts\User\PersonRepositoryInterface;
use App\Models\User\Person;

class PersonRepository implements PersonRepositoryInterface {
    public function store(array $data) {
        return Person::create($data);
    }

    public function update(Person $person, array $data) {
        $person = Person::findOrFail($person->id);

        $person->fill($data);

        $idPhotoChanged = $person->isDirty('id_photo');

        $person->save();

        if ($idPhotoChanged) {
            $user = $person->user;
            if ($user) {
                $user->verified_at = null;
                $user->save();
            }
        }

        return $person->fresh('user');
    }

    public function destroy(Person $person) {
        return Person::findOrFail($person->id)->delete();
    }
}
