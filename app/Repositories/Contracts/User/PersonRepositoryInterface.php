<?php

namespace App\Repositories\Contracts\User;

interface PersonRepositoryInterface {
    public function index();
    public function show($id);
    public function store(array $data);
    public function update($id, array $data);
    public function destroy($id);
}
