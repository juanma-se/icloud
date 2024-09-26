<?php

namespace App\Interfaces;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DocumentRepositoryInterface
{
    public function getAll(Request $request): Collection;

    public function find(int $id): ?Document;

    public function create(array $data): Document;

    public function update(int $id, array $data): Document;

    public function delete(int $id): bool;

    public function relevanceStats();

    public function monthlyApprovals();

    public function relevanceStatsWithDocuments();
}
