<?php

use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Models\Person;

class PersonRepository implements PersonRepositoryInterface
{
    public function create($data)
    {
        return Person::create($data);
    }

    public function findById($id)
    {
        return Person::with('user')->findOrFail($id);
    }
}

