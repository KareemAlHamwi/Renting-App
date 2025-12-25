<?php

namespace App\Repositories\Contracts\User;

interface PersonRepositoryInterface {
    public function store(array $data);
    public function update($id, array $data);
    public function destroy($id);
}
