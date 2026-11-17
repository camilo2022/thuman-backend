<?php

namespace App\Http\Resources\SeveranceType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SeveranceTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'severanceTypes' => $this->collection->map(function ($severanceType) {
                return [
                    'id' => $severanceType->id,
                    'item' => $severanceType->item,
                    'name' => $severanceType->name,
                    'description' => $severanceType->description,
                    'settings' => $severanceType->settings,
                    'created_at' => $this->formatDate($severanceType->created_at),
                    'updated_at' => $this->formatDate($severanceType->updated_at),
                    'deleted_at' => $this->formatDate($severanceType->deleted_at)
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
