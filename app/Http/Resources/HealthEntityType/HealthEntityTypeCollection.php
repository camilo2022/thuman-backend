<?php

namespace App\Http\Resources\HealthEntityType;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HealthEntityTypeCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'healthEntityTypes' => $this->collection->map(function ($healthEntityType) {
                return [
                    'id' => $healthEntityType->id,
                    'item' => $healthEntityType->item,
                    'name' => $healthEntityType->name,
                    'description' => $healthEntityType->description,
                    'settings' => $healthEntityType->settings,
                    'health_entities' => $healthEntityType->health_entities,
                    'created_at' => $this->formatDate($healthEntityType->created_at),
                    'updated_at' => $this->formatDate($healthEntityType->updated_at),
                    'deleted_at' => $this->formatDate($healthEntityType->deleted_at)
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
