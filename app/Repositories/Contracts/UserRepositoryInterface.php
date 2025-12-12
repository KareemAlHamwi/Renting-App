<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface {
    public function index();
    public function show($id);
    public function showByPhone($phone);
    public function showByUsername($username);
    public function store(array $data);
    public function update($id, array $data);
    public function updatePhone($id, $phone);
    public function updatePassword($id, $password);
    public function markAsVerified($id);
    public function is_verified($id);
    public function destroy($id);
}
