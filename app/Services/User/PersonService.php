<?php

namespace App\Services\User;

use App\Models\User\User;
use App\Repositories\Contracts\User\PersonRepositoryInterface;

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
        return $this->people->store($data);
    }


    public function updateForUser(User $user, array $data) {
        return $this->people->update($user->person_id, $data);
    }

    public function deleteForUser(User $user) {
        return $this->people->destroy($user->person_id);
    }

    // public function updatePerson(int $personId, array $data) {
    //     return $this->people->update($personId, $data);
    // }

    // public function deletePerson(int $personId) {
    //     return $this->people->destroy($personId);
    // }
}
