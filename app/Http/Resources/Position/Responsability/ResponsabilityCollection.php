<?php

namespace App\Http\Resources\Position\Responsability;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResponsabilityCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'responsabilities' => $this->collection->map(function ($responsability) {
                return [
                    'id' => $responsability->id,
                    'position' => $responsability->position,
                    'name' => $responsability->name,
                    'description' => $responsability->description,
                    'created_at' => $this->formatDate($responsability->created_at),
                    'updated_at' => $this->formatDate($responsability->updated_at)
                ];
            }),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('Y-m-d H:i:s') : $date;
    }

    protected function paginationMeta()
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
        ];
    }
}
