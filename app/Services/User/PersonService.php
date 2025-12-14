<?php

namespace App\Services\User;

use App\Repositories\Contracts\User\PersonRepositoryInterface;

class PersonService {
    private PersonRepositoryInterface $people;

    public function __construct(PersonRepositoryInterface $people) {
        $this->people = $people;
    }

    public function allPeople() {
        return $this->people->index();
    }

    public function findPersonById($id) {
        return $this->people->show($id);
    }

    public function createPerson(array $data) {
        return $this->people->store($data);
    }

    public function updatePerson($id, array $data) {
        return $this->people->update($id,$data);
    }

    public function deletePerson($id) {
        return $this->people->destroy($id);
    }
}
