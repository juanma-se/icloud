<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_page'  => $this->currentPage(),
            'data'          => $this->collection->toArray(),
            'from'          => $this->firstItem(),
            'per_page'      => $this->perPage(),
            'to'            => $this->lastItem(),
            'total'         => $this->total(),
        ];
    }
}
