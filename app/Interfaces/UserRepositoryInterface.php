<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function getAll($page = 1, $per_page = 25);

    public function getById($id);

    public function store(array $data);

    public function update(array $data, $id);

    public function destroy($id);

    public function register(array $data);
}
