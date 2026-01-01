<?php

namespace App\Repositories\Contracts\User;

use App\Models\User\Person;

interface PersonRepositoryInterface {
    public function store(array $data);
    public function update(Person $person, array $data);
    public function destroy(Person $person);
}
