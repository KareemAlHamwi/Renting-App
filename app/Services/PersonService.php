<?php

namespace App\Services;

use App\Repositories\Contracts\PersonRepositoryInterface;

class PersonService {
    private PersonRepositoryInterface $persons;

    public function __construct(PersonRepositoryInterface $persons) {
        $this->persons = $persons;
    }

    public function createPerson(array $data) {
        return $this->persons->create($data);
    }

    public function findPersonById($id) {
        return $this->persons->findBy($id);
    }
}
