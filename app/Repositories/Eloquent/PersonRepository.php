<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Models\Person;

class PersonRepository implements PersonRepositoryInterface {
    public function index() {
        return Person::all();
    }

    public function show($id) {
        return Person::with('user')->findOrFail($id);
    }

    public function store(array $data) {
        return Person::create($data);
    }

    public function update($id, array $data) {
        $person = Person::findOrFail($id);
        $person->update($data);

        return $person;
    }

    public function destroy($id) {
        return Person::findOrFail($id)->delete();
    }
}
