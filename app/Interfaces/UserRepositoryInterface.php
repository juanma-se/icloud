<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function getAll(Request $request): Collection;

    public function register(array $data);
}
