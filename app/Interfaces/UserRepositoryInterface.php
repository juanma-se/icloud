<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function getAll(Request $request): LengthAwarePaginator;

    public function register(array $data);
}
